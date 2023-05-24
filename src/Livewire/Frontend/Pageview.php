<?php

namespace Secondnetwork\Kompass\Livewire\Frontend;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Secondnetwork\Kompass\Models\Block;
use Secondnetwork\Kompass\Models\Datafields;
use Secondnetwork\Kompass\Models\File;
use Secondnetwork\Kompass\Models\Page;

class Pageview extends Component
{
    public $page;

    public $blocks;

    public $blocks_id;

    public $datafields;

    public $fields;

    public $blocktemplates;

    public $blocks_collapse;

    public function mount($slug = null)
    {
        $this->page = $this->ResolvePath($slug);

        $this->blocks = Cache::rememberForever('kompass_block_'.$slug, function () {
            return Block::where('page_id', $this->page->id)->where('status', 'public')->orderBy('order', 'asc')->where('subgroup', null)->with('children')->get();
        });

        $this->blocks_id = Cache::rememberForever('kompass_block_id_'.$slug, function () {
            return Block::where('page_id', $this->page->id)->orderBy('order', 'asc')->pluck('id');
        });
        Arr::collapse($this->blocks_id);

        if (! Cache::has('kompass_field_'.$slug)) {
            $this->datafields = Datafields::whereIn('block_id', $this->blocks_id)->get()->mapToGroups(function ($item, $key) {
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
        if ($slug == null) {
            $is_front = Page::where('layout', 'is_front_page')->where('status', 'public')->firstOrFail();

            if ($is_front) {
                return $is_front;
            }
        }

        $page = Page::where('slug', $slug)->where('status', 'public')->firstOrFail();
        if ($page) {
            return $page;
        }
    }

    public function get_field($slug, $blockis = null, $class = null, $size = null)
    {
        foreach ($this->fields[$blockis] as $value) {
            if ($value->slug == $slug) {
                if ($value->type == 'image' && $value->data != null) {
                    $file = file::where('id', $value->data)->first();
                    if ($file) {
                        if ($size) {
                            $sizes = '_'.$size;
                            return '<picture>
                            <source type="image/avif" srcset="'.asset('storage'.$file->path.'/'.$file->slug).'.avif">
                            <img class="'.$class.'" src="'.asset('storage'.$file->path.'/'.$file->slug.$sizes.'.'.$file->extension).'" alt="'.$file->alt.'" />
                            </picture>
                            ';
                        } else {
                            return '<picture>
                            <source type="image/avif" srcset="'.asset('storage'.$file->path.'/'.$file->slug).'.avif">
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
    //         // title: $this->page->title,
    //         description: $this->page->meta_description,
    //         // author: $this->author->fullName,
    //     );
    // }

    public function render()
    {
        return view('livewire.pageview', [
            // 'SEOData' => $this->getDynamicSEOData()
        ])->layout('layouts.main');
    }
}
