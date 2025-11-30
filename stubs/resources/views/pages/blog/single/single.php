<?php

use Livewire\Component;
use Illuminate\Support\Arr;
use Secondnetwork\Kompass\Models\Post;
use Illuminate\Support\Facades\Request;
use Secondnetwork\Kompass\Models\Block;
use Secondnetwork\Kompass\Models\ErrorLog;
use Secondnetwork\Kompass\Models\Redirect;
use Secondnetwork\Kompass\Models\Datafield;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

new class extends Component
{
    public $post;

    public $blocks;

    public $blocks_id;

    public $datafields;

    public $fields;

    public $blocktemplates;

    public $blocks_collapse;

    public function mount(Request $request, $slug = null)
    {
        try {
        $this->ResolvePath($slug);
        if ($this->post instanceof Redirect) {
            return redirect($this->post->to_url, $this->post->status_code);
        }
        //blockable_type
        $blocks = Block::where('blockable_type', 'post')->where('blockable_id', $this->post->id)->where('status', 'published')->orderBy('order', 'asc')->where('subgroup', null)->with('children')->get();

        if ($blocks->isNotEmpty()) {
            $this->blocks = $blocks;
            $blocks_id = Block::where('blockable_id', $this->post->id)->orderBy('order', 'asc')->pluck('id');

            Arr::collapse($blocks_id);

            $this->fields = Datafield::whereIn('block_id', $blocks_id)->get();
        }

    } catch (NotFoundHttpException $e) {
        $this->log404Error($request->path(), $e);
        throw $e;
    }

    }

    public function ResolvePath($slug)
    {
        $user = auth()->user();

        $privilegedRoles = ['admin', 'manager', 'editor', 'author', 'writer'];
        $canSeeDrafts = $user && $user->hasAnyRole($privilegedRoles);

        $query = Post::where('slug', $slug);

        if (!$canSeeDrafts) {
            $query->whereNot('status', 'draft');
        }

        $this->post = $query->first();

        // Redirect Prüfung
        if (! $this->post) {
            $this->post = Redirect::where('old_url', '/'.$slug)->first();
        }

        if (! $this->post) {
            throw new NotFoundHttpException('Post not found - '.$slug);
        }
    }

    public function get_gallery($blockis = null)
    {

        foreach ($this->fields[$blockis] as $value) {

            if ($value->type == 'gallery' && $value->data != null) {
                $file = file::where('id', $value->data)->first();
                if ($file) {
                    // $dataarray[] =   asset('storage' . $file->path . '/' . $file->slug) . '.avif';

                    $dataarray[] = '<picture>
                    <source type="image/avif" srcset="'.asset('storage'.$file->path.'/'.$file->slug).'.avif">
                    <img class="aspect-square max-w-[clamp(10rem,28vmin,20rem)] rounded-md object-cover shadow-md"
                    src="'.asset('storage'.$file->path.'/'.$file->slug.'.'.$file->extension).'" alt="'.$file->alt.'" />
                    </picture>';
                }
            }

        }

        $str = implode('', $dataarray);

        return $str;
    }

    public function get_field($type, $blockis = null, $class = null, $size = null)
    {

        foreach ($this->fields as $value) {

            if ($blockis == $value->block_id) {
                if ($value->type == $type) {
                    if ($value->type == 'video' && $value->data != null) {
                        $file = file::where('id', $value->data)->first();

                        return $file->path.'/'.$file->slug.'.'.$file->extension;
                    }
                    if ($value->type == 'poster' && $value->data != null) {
                        $file = file::where('id', $value->data)->first();

                        return $file->path.'/'.$file->slug.'.'.$file->extension;
                    }
                    if ($value->type == 'image' && $value->data != null) {
                        $file = file::where('id', $value->data)->first();
                        if ($file) {
                            if ($size) {
                                $sizes = '_'.$size;

                                return '<picture>
                                <source type="image/avif" srcset="'.asset('storage/'.$file->path.'/'.$file->slug).'.avif">
                                <img class="'.$class.'" src="'.asset('storage'.$file->path.'/'.$file->slug.$sizes.'.'.$file->extension).'" alt="'.$file->alt.'" />
                                </picture>
                                ';
                            } else {
                                return '<picture>
                                <source type="image/avif" srcset="'.asset('storage/'.$file->path.'/'.$file->slug).'.avif">
                                <img class="'.$class.'" src="'.asset('storage'.$file->path.'/'.$file->slug.'.'.$file->extension).'" alt="'.$file->alt.'" />
                                </picture>
                                ';
                            }
                        }

                        return '';
                    }

                    return $value->data;
                }

            }

        }
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

    // public function render()
    // {
    //     return view('livewire.pages.blog.single', [
    //         // 'SEOData' => $this->getDynamicSEOData()
    //     ])->layout('layouts.main');
    // }
};