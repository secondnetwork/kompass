<?php

namespace Secondnetwork\Kompass\Livewire\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Secondnetwork\Kompass\Models\Block;
use Secondnetwork\Kompass\Models\Datafield;
use Secondnetwork\Kompass\Models\ErrorLog;
use Secondnetwork\Kompass\Models\File;
use Secondnetwork\Kompass\Models\Page;
use Secondnetwork\Kompass\Models\Redirect;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Pageview extends Component
{
    public $page;

    public $blocks;

    public $fields;

    public $settings;

    public function mount(Request $request, $slug = null)
    {
        // try-catch block is important
        try {
            $this->resolvePageAndRedirect($request->segment(1), $slug);
            if ($this->page instanceof Redirect) {
                return redirect($this->page->to_url, $this->page->status_code);
            }
            if (! empty($this->page->new_url)) {
                return redirect($this->page->new_url, $this->page->status_code);
            }

            $this->loadBlocks($slug);
            // $this->loadFields($slug);

        } catch (NotFoundHttpException $e) {
            $this->log404Error($request->path(), $e);
            throw $e;
        }
    }

    private function resolvePageAndRedirect($land, $slug): void
    {
        $landurl = in_array($land, config('kompass.available_locales')) ? $land : null;
        if ($slug === null) {
            $this->page = Page::query()
                ->where('land', $landurl)
                ->where('layout', 'is_front_page')
                ->where('status', 'published')
                ->first();

        } else {
            $this->page = Page::query()
                ->where('land', $landurl)
                ->where('slug', $slug)
                ->whereNot('status', 'draft')
                ->first();
        }

        if (! $this->page) {
            $this->page = Redirect::where('old_url', '/'.$slug)->first();
        }
        if (! $this->page) {
            throw new NotFoundHttpException('Page not found');
        }
    }

    private function loadBlocks($slug)
    {
        $this->blocks = Cache::rememberForever('kompass_block_'.$slug, function () {
            return Block::where('blockable_type', 'page')
                ->where('blockable_id', $this->page->id)
                ->where('status', 'published')
                ->orderBy('order', 'asc')
                ->where('subgroup', null)
                ->with(['children' => function ($query) {
                    $query->where('status', 'published');
                }, 'datafield', 'meta'])
                ->get();
        });
    }

    private function loadFields($slug)
    {
        $blockIds = $this->blocks->pluck('id');

        $this->fields = Cache::rememberForever('kompass_field_'.$slug, function () use ($blockIds) {
            return Datafield::whereIn('block_id', $blockIds)->get()->groupBy('block_id');
        });
    }

    public function getGallery($blockId = null)
    {
        if (! isset($this->fields[$blockId])) {
            return '';
        }

        $dataarray = [];

        foreach ($this->fields[$blockId] as $value) {
            if ($value->type === 'gallery' && $value->data !== null) {
                $file = File::find($value->data);
                if ($file) {
                    $dataarray[] = $this->generateImageTag($file);
                }
            }
        }

        return implode('', $dataarray);
    }

    private function generateImageTag($file)
    {
        return '<picture>
            <source type="image/avif" srcset="'.asset('storage'.$file->path.'/'.$file->slug).'.avif">
            <img class="aspect-square max-w-[clamp(10rem,28vmin,20rem)] rounded-md object-cover shadow-md"
            src="'.asset('storage'.$file->path.'/'.$file->slug.'.'.$file->extension).'" alt="'.$file->alt.'" />
            </picture>';
    }

    public function getField($type, $blockId = null, $class = null, $size = null)
    {
        if (! isset($this->fields[$blockId])) {
            return '';
        }

        foreach ($this->fields[$blockId] as $value) {
            if ($value->type === $type && $value->data !== null) {
                $file = File::find($value->data);
                if ($file && in_array($value->type, ['video', 'poster', 'image'])) {
                    return $this->generateMediaTag($file, $value->type, $class, $size);
                }

                return $value->data;
            }
        }

        return '';
    }

    private function generateMediaTag($file, $type, $class, $size)
    {
        $sizes = $size ? '_'.$size : '';
        if ($type === 'image') {
            return '<picture>
                <source type="image/avif" srcset="'.asset('storage/'.$file->path.'/'.$file->slug).'.avif">
                <img class="'.$class.'" src="'.asset('storage'.$file->path.'/'.$file->slug.$sizes.'.'.$file->extension).'" alt="'.$file->alt.'" />
                </picture>';
        }

        return $file->path.'/'.$file->slug.'.'.$file->extension;
    }

    protected function log404Error($url, $e)
    {
        ErrorLog::create([
            'url' => $url,
            'message' => $e->getMessage(),
            'user_id' => auth()->id(), // Optional, um Benutzer-ID zu loggen
            'ip_address' => request()->ip(),
            'status_code' => 404, // Setze den Statuscode auf 404
        ]);
    }

    public function render()
    {
        return view('livewire.pageview')->layout('layouts.main');
    }
}
