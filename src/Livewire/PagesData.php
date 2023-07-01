<?php

namespace Secondnetwork\Kompass\Livewire;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Models\Block;
use Secondnetwork\Kompass\Models\Blockfields;
use Secondnetwork\Kompass\Models\Blocktemplates;
use Secondnetwork\Kompass\Models\Datafields;
use Secondnetwork\Kompass\Models\Page;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\Dump;

class PagesData extends Component
{
    // use WithPagination;
    /**
     * The component's listeners.
     *
     * @var array
     */
    public $page_id;

    public $page;

    public $title;

    public $blocks;

    public $blockgroupId;

    public $fields;

    public $newName;

    public $blocktemplates;

    public $getIdField;

    public $arrayIdField;

    public $FormAdjustments = false;

    public $FormBlocks = false;

    public $FormMedia = false;

    public $FormDelete = false;

    public $FormAdd = false;

    public $FormEdit = false;

    public $Editorjs;

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
        'refreshComponent' => '$refresh',
        'refreshmedia' => 'call_emit_reset',

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

        // $this->call_emit_reset();
    }

    public function mount($id)
    {
        $this->page_id = $id;
        $this->page = Page::findOrFail($id);
        $this->blocks = Block::where('page_id', $id)->orderBy('order', 'asc')->where('subgroup', null)->with('children')->get();
        // $this->blocks = Block::where('page_id', $id)->orderBy('order', 'asc')->get();
        // $this->blocks = Block::whereNull('subgroup')->with(['children'])->get();

        $blocks_id = Block::where('page_id', $id)->orderBy('order', 'asc')->pluck('id');

        Arr::collapse($blocks_id);

        $this->fields = Datafields::whereIn('block_id', $blocks_id)->get();

        $this->blocktemplates = Blocktemplates::orderBy('order', 'asc')->get()->all();
        // $this->blockschildren = $this->tree($this->blocks);
        // $this->blockfields = Blockfields::where('blocktemplate_id',$id)->orderBy('order')->get();
    }

    public function selectItem($itemId, $action, $groupId = null)
    {
        $this->selectedItem = $itemId;
        $this->blockgroupId = $groupId;

        if ($action == 'addBlock') {
            $this->FormBlocks = true;
        }
        if ($action == 'update') {
        }
        if ($action == 'addMedia') {
            $this->getIdField = $itemId;
            $this->FormMedia = true;
            $this->emit('getIdField_changnd', $this->getIdField, 'page');
            $this->emit('getIdBlock', $this->blockgroupId);
        }
        if ($action == 'deleteblock') {
            $this->FormDelete = true;
        }
    }

    public function addBlock($pageID, $blocktemplatesID, $name, $slug, $grid, $blockType = null)
    {
        // Layout *popout or full *** alignment* left or right
        if ($blockType == 'tables') {
            $blockTypeData = ['layout' => 'popout', 'alignment' => 'left', 'tables' => '2', 'type' => $blockType];
        } else {
            $blockTypeData = ['layout' => 'popout', 'alignment' => 'left', 'slider' => '', 'type' => $blockType];
        }

        $block = Block::create([
            'page_id' => $pageID,
            'name' => $name,
            'subgroup' => $this->blockgroupId,
            'set' => $blockTypeData,
            'status' => 'published',
            'slug' => $slug,
            'grid' => $grid,
            'order' => '999',
        ]);
        if ($blockType == 'tables') {
            Datafields::create([
                'block_id' => $block->id,
                'name' => 'tables',
                'slug' => 'tables',
                'type' => 'tables',
                'grid' => '1',
                'order' => '1',
            ]);
        }

        if ($blocktemplatesID != null) {
            $get_blocks = Blockfields::where('blocktemplate_id', $blocktemplatesID)->get();

            foreach ($get_blocks as $value) {
                Datafields::create([
                    'block_id' => $block->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'type' => $value->type,
                    'grid' => $value->grid,
                    'order' => $value->order,
                ]);
            }
        }
        $this->FormBlocks = false;
        $this->call_emit_reset();
    }

    public function refreshmedia()
    {
        $this->emit('refreshComponent');
        $this->emit('status');
    }

    public function call_emit_reset()
    {
        $this->mount($this->page_id);

        $this->FormMedia = false;
        $this->emit('refreshComponent');
        $this->emit('status');

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

        $this->call_emit_reset();
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
        $this->call_emit_reset();
    }

    public function savename($id)
    {
        if ($this->newName != null) {
            $block = block::findOrFail($id);
            $block->update(['name' => $this->newName]);
        }
        $this->call_emit_reset();
    }

    public function set($id, $set, $status)
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

        $this->call_emit_reset();
    }

    public function status($id, $status)
    {
        if ($status == 'draft') {
            Block::where('id', $id)->update(['status' => 'draft']);
            $this->emit('status');
        }
        if ($status == 'published') {
            Block::where('id', $id)->update(['status' => 'published']);
            $this->emit('status');
        }
        $this->call_emit_reset();
    }

    public function statusPage($id, $status)
    {
        if ($status == 'draft') {
            Page::where('id', $id)->update(['status' => 'draft']);
        }
        if ($status == 'published') {
            Page::where('id', $id)->update(['status' => 'published']);
        }

        $this->call_emit_reset();
    }

    public function update($id, $publisheded = null)
    {
        $page = Page::findOrFail($id);

        // $this->getDynamicSEOData();
        // $page->addSEO();
        $this->emit('savedatajs');

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
            $this->emit('status');
        }

        $page->update([
            'title' => $validateData['page']['title'],
            'meta_description' => $validateData['page']['meta_description'],
            'layout' => $validateData['page']['layout'],
            'status' => $validateData['page']['status'],
            'password' => $validateData['page']['password'],
            'begin_at' => $validateData['page']['begin_at'],
            'end_at' => $validateData['page']['end_at'],
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

        $this->call_emit_reset();
    }

    public function removemedia($id)
    {
        Datafields::whereId($id)->update(['data' => null]);
        $this->call_emit_reset();
    }

    public function removemediaIngallery($id)
    {
        Datafields::whereId($id)->delete();
        $this->call_emit_reset();
    }

    public function delete() //delete block
    {
        Datafields::where('block_id', $this->selectedItem)->delete();
        block::destroy($this->selectedItem);
        $this->FormDelete = false;
        // $this->mount($this->selectedItem);
        $this->call_emit_reset();
    }

    public function render()
    {
        return view('kompass::livewire.pages.pages-show')
            ->layout('kompass::admin.layouts.app');
    }

    public function updateOrder($list)
    {
        foreach ($list as $items) {
            // $boardgroub = $itemg['value'];
            foreach ($items['items'] as $item) {
                Block::whereId($item['value'])->update(['order' => $item['order']]);
            }
        }

        $this->call_emit_reset();
        $this->emit('status');
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
        $this->call_emit_reset();
    }

    public function updateBlocksOrder($list)
    {
        foreach ($list as $item) {
            Block::whereId($item['value'])->update(['order' => $item['order']]);
            // foreach($itemg['items'] as $item){
            //     Page::whereId($item['value'])->update(['order' => $item['order']]);
            // }
        }

        $this->call_emit_reset();
        $this->emit('status');
        // Page::whereId($list['value'])->update(['order' => $list['order']]);
    }
}
