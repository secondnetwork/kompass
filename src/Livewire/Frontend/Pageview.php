<?php

namespace Secondnetwork\Kompass\Livewire\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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

    public $page_frontNotFound = false;

    public $blocks;

    public $fields;

    public $settings;

    public function mount(Request $request, $locale = null, $slug = null)
    {
        try {
            $localesData = setting('global.available_locales');
            if ($localesData) {
                $availableLocales = is_array($localesData) ? $localesData : json_decode($localesData, true);
            } else {
                $availableLocales = ['de', 'en'];
            }

            $defaultLocale = $availableLocales[0] ?? 'de';

            if (setting('global.multilingual')) {
                // Handle routes like /{slug} where only one param is passed
                if ($slug === null && $locale !== null) {
                    if (! in_array($locale, $availableLocales)) {
                        $slug = $locale;
                        $locale = $defaultLocale;
                    }
                }
                $land = $locale ?? $defaultLocale;
            } else {
                if ($slug === null && $locale !== null) {
                    $slug = $locale;
                }
                $land = $defaultLocale;
            }

            $this->resolvePageAndRedirect($land, $slug);

            if ($this->page instanceof Redirect) {
                return redirect($this->page->to_url, $this->page->status_code);
            }
            if (! empty($this->page->new_url)) {
                return redirect($this->page->new_url, $this->page->status_code);
            }
            if ($this->page && ! $this->page_frontNotFound) {
                $this->loadBlocks($this->page->slug);
            }

        } catch (NotFoundHttpException $e) {
            $this->log404Error($request->path(), $e);
            throw $e;
        }
    }

    private function resolvePageAndRedirect($land, $slug): void
    {
        $localesData = setting('global.available_locales');
        if ($localesData) {
            $availableLocales = is_array($localesData) ? $localesData : json_decode($localesData, true);
        } else {
            $availableLocales = ['de', 'en'];
        }
        $defaultLocale = $availableLocales[0] ?? 'de';

        $isMultilingual = setting('global.multilingual');
        Log::info('Multilingual: '.var_export($isMultilingual, true));
        $landurl = ($isMultilingual && in_array($land, $availableLocales)) ? $land : $defaultLocale;
        Log::info('Landurl: '.$landurl);

        if ($slug === null) {
            $this->page = Page::query()
                // ->where('layout', 'is_front_page')
                ->where('status', 'published')
                ->first();

            if (! $this->page) {
                Log::error('Frontpage not found in database.');
                $this->page_frontNotFound = true;

                return;
            }
        } else {
            $this->page = Page::query()
                ->where(function ($query) use ($landurl): void {
                    $query->where('land', $landurl)
                        ->orWhere('land', '')
                        ->orWhereNull('land');
                })
                ->where('slug', $slug)
                ->whereNot('status', 'draft')
                ->orderByRaw('CASE WHEN land = ? THEN 0 ELSE 1 END', [$landurl])
                ->first();
        }

        if (! $this->page) {
            $this->page = Redirect::where('old_url', '/'.$slug)->first();
        }
        if (! $this->page && ! $this->page_frontNotFound) {
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
                ->with(['children' => function ($query): void {
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
