<?php

namespace Secondnetwork\Kompass\Livewire;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;
use Intervention\Image\Facades\Image;
use Secondnetwork\Kompass\Models\Block;
use Secondnetwork\Kompass\Models\Blockfields;
use Secondnetwork\Kompass\Models\Blocktemplates;
use Secondnetwork\Kompass\Models\Datafields;
use Secondnetwork\Kompass\Models\Page;

#[Layout('kompass::admin.layouts.app')]
class PagesData extends Component
{
    use WithPagination;

    /**
     * The component's listeners.
     *
     * @var array
     */
    #[Locked]
    public $selectedItem;

    #[Locked]
    public $getId;

    public $page;

    public $title;

    public $blocks = [];

    public $blockgroupId;

    public $fields = [];

    public $newName;

    public $blocktemplates;

    public $arrayIdField;

    public $iconclass;

    public $FormAdjustments = false;

    public $FormBlocks = false;

    public $FormMediaVideo = false;

    public $FormMedia = false;

    public $FormDelete = false;

    public $FormAdd = false;

    public $FormEdit = false;

    public $Editorjs;

    public $oembedUrl;

    public $data;

    public $selected = [];

    protected $rules = [

        'page.title' => 'required|string|min:3',
        'page.meta_description' => '',
        'page.slug' => '',
        'page.layout' => '',
        'page.status' => '',
        'page.password' => '',
        'page.begin_at' => '',
        'page.end_at' => '',
        'blocks.*.id' => '',
        'blocks.*.name' => '',
        'fields.*.id' => '',
        'fields.*.data' => '',

    ];

    protected $listeners = [
        'editorjssave' => 'saveEditorState',
        'refreshmedia' => 'resetPageComponent',

    ];

    public function saveEditorState($editorJsonData, $id)
    {

        if (! empty($editorJsonData)) {

            Datafields::whereId($id)->update(['data' => $editorJsonData]);
            // foreach($itemg['items'] as $item){
            //     block::whereId($item['value'])->update(['order' => $item['order']]);
            // }
            // dump($itemg);

        }

        $this->resetPageComponent();
    }

    public function mount($id)
    {
        $this->page = Page::findOrFail($id);

        $blocks = Block::where('blockable_type', 'page')->where('blockable_id', $id)->orderBy('order', 'asc')->where('subgroup', null)->with('children')->get();

        if ($blocks->isNotEmpty()) {
            $this->blocks = $blocks;
            $blocks_id = Block::where('blockable_id', $id)->orderBy('order', 'asc')->pluck('id');

            Arr::collapse($blocks_id);

            $this->fields = Datafields::whereIn('block_id', $blocks_id)->get();
        }

        $this->blocktemplates = Blocktemplates::orderBy('order', 'asc')->get()->all();
        // $this->blockschildren = $this->tree($this->blocks);
        // $this->blockfields = Blockfields::where('blocktemplate_id',$id)->orderBy('order')->get();
    }

    public function selectitem($action, $itemId, $fieldOrPageName = null, $blockgroupId = null)
    {
        $this->getId = $itemId;

        if ($action == 'addBlock') {
            $this->blockgroupId = $blockgroupId;
            $this->FormBlocks = true;
        }
        if ($action == 'update') {
        }
        if ($action == 'addMedia') {
            $this->FormMedia = true;
            $this->dispatch('getIdField_changnd', $this->getId, $fieldOrPageName);
            $this->dispatch('getIdBlock', $blockgroupId);
        }
        if ($action == 'deleteblock') {
            $this->FormDelete = true;
        }
    }

    public function addoEmbed($blockId)
    {
        Datafields::create([
            'block_id' => $blockId,
            'type' => 'oembed',
            'data' => $this->oembedUrl,
            'order' => '1',
        ]);

        $videoEmbed = videoEmbed($this->oembedUrl);
        if ($videoEmbed['type'] == 'youtube') {
            $thumbnailName = $videoEmbed['id'].'.jpg';
            $thumbnailUrl = 'https://i.ytimg.com/vi/'.$videoEmbed['id'].'/maxresdefault.jpg';
    
            if (Storage::disk('public')->missing('thumbnails-video/'.$thumbnailName)) 
            {
                $thumbnailContents = file_get_contents($thumbnailUrl);
                if ($thumbnailContents) {
                    $image = Image::make($thumbnailContents);
                    Storage::disk('public')->put('thumbnails-video/'.$thumbnailName, $image->encode('jpg', 60)->encoded);
                }

            }
        }


        $this->resetPageComponent();
    }

    public function addBlock($blocktemplatesID, $name, $type, $iconclass = null)
    {
        // Layout *popout or full *** alignment* left or right

        $blockTypeData = ['layout' => 'popout', 'alignment' => 'left', 'slider' => ''];
        $tempBlock = Blocktemplates::where('id', $blocktemplatesID)->first();

        $block = $this->page->blocks()->create([
            'name' => $name,
            'subgroup' => $this->blockgroupId,
            'set' => $blockTypeData,
            'status' => 'published',
            'grid' => $tempBlock->grid ?? '1',
            'iconclass' => $tempBlock->iconclass ?? $iconclass,
            'type' => $type,
            'order' => '999',
        ]);
        if ($type == 'wysiwyg') {
            Datafields::create([
                'block_id' => $block->id,
                'type' => 'wysiwyg',
                'order' => '1',
            ]);
        }

        if ($blocktemplatesID != null) {
            $get_blocks = Blockfields::where('blocktemplate_id', $blocktemplatesID)->get();

            foreach ($get_blocks as $value) {
                Datafields::create([
                    'block_id' => $block->id,
                    'type' => $value->type,
                    'grid' => $value->grid,
                    'order' => $value->order,
                ]);
            }
        }
        $this->FormBlocks = false;

        $this->resetPageComponent();
    }

    public function refreshmedia()
    {
        $this->dispatch('status');
    }

    public function resetPageComponent()
    {

        $this->mount($this->page->id);
        $this->FormMedia = false;
        $this->dispatch('status');

        // return redirect()->to('admin');
    }

    public function clone($id)
    {
        $block = block::find($id);
        $newblock = $block->replicate();

        $newblock->created_at = Carbon::now();

        $newblock->push();

        $fields = Datafields::where('block_id', $id)->get();

        $fields->each(function ($item, $key) use ($newblock) {
            $copyitem = $item->replicate();
            $copyitem->block_id = $newblock->id;
            $copyitem->save();
        }, );

        $this->resetPageComponent();
    }

    public function selected($id)
    {
        $data = Datafields::findOrFail($id);

        if ($data->data == 0) {
            $data->update([
                'data' => '1',
            ]);
        } else {
            $data->update([
                'data' => '0',
            ]);
        }
        $this->resetPageComponent();
    }

    public function savename($id)
    {
        if ($this->newName != null) {
            $block = block::findOrFail($id);
            $block->update(['name' => $this->newName]);
        }
        $this->resetPageComponent();
    }

    public function updateGrid($id, $grid)
    {
        $setblock = Block::findOrFail($id);
        $setblock->update(['grid' => $grid]);
        $this->resetPageComponent();
    }

    public function saveset($id, $set, $status)
    {

        $setblock = Block::findOrFail($id);

        if ($set == 'layout') {
            $setblock->update(['set->layout' => $status]);
        }
        if ($set == 'alignment') {
            $setblock->update(['set->alignment' => $status]);
        }
        if ($set == 'slider') {
            $setblock->update(['set->slider' => $status]);
        }

        $this->resetPageComponent();
    }

    public function status($id, $status)
    {
        if ($status == 'draft') {
            Block::where('id', $id)->update(['status' => 'draft']);
            $this->dispatch('status');
        }
        if ($status == 'published') {
            Block::where('id', $id)->update(['status' => 'published']);
            $this->dispatch('status');
        }
        $this->resetPageComponent();
    }

    public function statusPage($id, $status)
    {
        if ($status == 'draft') {
            Page::where('id', $id)->update(['status' => 'draft']);
        }
        if ($status == 'published') {
            Page::where('id', $id)->update(['status' => 'published']);
        }

        $this->resetPageComponent();
    }

    public function update($id, $publisheded = null)
    {

        $page = Page::findOrFail($id);

        // $this->getDynamicSEOData();
        // $page->addSEO();
        $this->dispatch('savedatajs');

        $validateData = $this->validate();

        $titlePageDB = Str::slug($page->title, '-', 'de');
        $slugPageDB = $page->slug;
        $titlePage = Str::slug($validateData['page']['title'], '-', 'de');
        $slugPage = $validateData['page']['slug'];

        $placeObj = new Page;

        if ($titlePage != $titlePageDB) {
            $numericalPrefix = 1;
            while (1) {
                $newSlug = $titlePage.'-'.$numericalPrefix++;
                $newSlug = Str::slug($newSlug, '-', 'de');
                $checkSlug = $placeObj->whereSlug($newSlug)->exists();
                if (! $checkSlug) {
                    $slugNameURL = $newSlug; //New Slug
                    break;
                }
            }
        } else {
            //Slug do not exists. Just use the selected Slug.
            $slugNameURL = $titlePage;
        }

        if ($publisheded == true) {
            Page::where('id', $id)->update(['status' => 'published']);
            $this->dispatch('status');
        }

        $page->update([
            'title' => $validateData['page']['title'],
            'meta_description' => $validateData['page']['meta_description'],
            'layout' => $validateData['page']['layout'],
            'status' => $validateData['page']['status'],
            // 'password' => $validateData['page']['password'],
            // 'begin_at' => $validateData['page']['begin_at'],
            // 'end_at' => $validateData['page']['end_at'],
        ]);

        $page->update([
            'slug' => $slugNameURL,
            'updated_at' => Carbon::now(),
        ]);

        if (! empty($validateData['blocks'])) {
            foreach ($validateData['blocks'] as $itemg) {
                Block::whereId($itemg['id'])->update($itemg);
            }
        }

        if (! empty($validateData['fields'])) {

            foreach ($validateData['fields'] as $itemg) {
                Datafields::whereId($itemg['id'])->update($itemg);
                // foreach($itemg['items'] as $item){
                //     block::whereId($item['value'])->update(['order' => $item['order']]);
                // }
                // dump($itemg);
            }
        }

        $this->resetPageComponent();
    }

    public function removemedia($id)
    {
        Datafields::whereId($id)->delete();
        $this->resetPageComponent();
    }

    public function delete() //delete block
    {
        Datafields::where('block_id', $this->getId)->delete();
        block::destroy($this->getId);
        $this->FormDelete = false;
        $this->resetPageComponent();
    }

    public function updateOrder($list)
    {

        foreach ($list as $items) {
            // $boardgroub = $itemg['value'];
            foreach ($items['items'] as $item) {
                Block::whereId($item['value'])->update(['order' => $item['order']]);
            }
        }

        $this->resetPageComponent();
        $this->dispatch('status');
        // Page::whereId($list['value'])->update(['order' => $list['order']]);
    }

    public function updateItemsOrder($list)
    {
        foreach ($list as $itemgroup) {
            Block::whereId($itemgroup['value'])->update(['order' => $itemgroup['order']]);

            if ($itemgroup['items']) {
                foreach ($itemgroup['items'] as $item) {
                    if ($item['value'] != $itemgroup['value']) {
                        Block::whereId($item['value'])->update(['order' => $item['order'], 'subgroup' => $itemgroup['value']]);
                    }
                }
            }
        }
        $this->resetPageComponent();
    }

    public function updateBlocksOrder($list)
    {
        foreach ($list as $item) {
            Block::whereId($item['value'])->update(['order' => $item['order']]);
            // foreach($itemg['items'] as $item){
            //     Page::whereId($item['value'])->update(['order' => $item['order']]);
            // }
        }

        $this->resetPageComponent();
        $this->dispatch('status');
        // Page::whereId($list['value'])->update(['order' => $list['order']]);
    }

    //
    public function render()
    {

        return view('kompass::livewire.pages.pages-show')
            ->layout('kompass::admin.layouts.app');
    }
}
