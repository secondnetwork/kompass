<?php

namespace Secondnetwork\Kompass\Livewire\Frontend;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Secondnetwork\Kompass\Models\Block;
use Secondnetwork\Kompass\Models\Datafields;
use Secondnetwork\Kompass\Models\File;

use Secondnetwork\Kompass\Models\Post;

class Blogview extends Component
{
    public $post;

    public $blocks;

    public $blocks_id;

    public $datafields;

    public $fields;

    public $blocktemplates;

    public $blocks_collapse;

    public function mount($slug = null)
    {
        $this->post = $this->ResolvePath($slug);

        if (! empty($this->post->new_url)) {
            return redirect($this->post->new_url, $this->post->status_code);
        }
        //blockable_type
        $this->blocks = Cache::rememberForever('kompass_block_'.$slug, function () {
            return Block::where('blockable_type', 'post')->where('blockable_id', $this->post->id)->where('status', 'published')->orderBy('order', 'asc')->where('subgroup', null)->with('children')->get();
        });

        $this->blocks_id = Cache::rememberForever('kompass_block_id_'.$slug, function () {
            return Block::where('blockable_type', 'post')->where('blockable_id', $this->post->id)->orderBy('order', 'asc')->pluck('id');
        });
        Arr::collapse($this->blocks_id);

        if (! Cache::has('kompass_field_'.$slug)) {
            $this->datafields = Datafields::query()->whereIn('block_id', $this->blocks_id)->get()->mapToGroups(function ($item, $key) {
                return [
                    $item['block_id'] => $item,
                ];
            });
        }
        $this->fields = Cache::rememberForever('kompass_field_'.$slug, function () {
            return $this->datafields;
        });

    }

    public function ResolvePath($slug)
    {

        $post = Post::where('slug', $slug)->whereNot('status', 'draft')->first();

        if (! empty($post)) {
            return $post;
        } else {
            return '';
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
        foreach ($this->fields[$blockis] as $value) {
            if ($value->type == $type) {
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

    // public function getDynamicSEOData(): SEOData
    // {

    //     return new SEOData(
    //         // title: $this->post->title,
    //         description: $this->post->meta_description,
    //         // author: $this->author->fullName,
    //     );
    // }

    public function render()
    {
        return view('livewire.pages.blog.single', [
            // 'SEOData' => $this->getDynamicSEOData()
        ])->layout('layouts.main');
    }
}
