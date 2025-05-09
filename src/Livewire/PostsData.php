<?php

namespace Secondnetwork\Kompass\Livewire;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Models\Block;
use Secondnetwork\Kompass\Models\Blockfields;
use Secondnetwork\Kompass\Models\Blocktemplates;
use Secondnetwork\Kompass\Models\Datafield;
use Secondnetwork\Kompass\Models\Post;
use Secondnetwork\Kompass\Models\Setting;

#[Layout('kompass::admin.layouts.app')]
class PostsData extends Component
{
    // use WithPagination;
    /**
     * The component's listeners.
     *
     * @var array
     */
    #[Locked]
    public $selectedItem;

    #[Locked]
    public $getId;

    public $post;

    public $title;

    public $status;

    public $description;

    public $layout;

    public $blocks = [];

    public $blockgroupId;

    public $fields = [];

    public $datafield = [];

    public $newName;

    public $blocktemplates;

    public $arrayIdField;

    public $iconclass;

    public $FormAdjustments = false;

    public $FormBlocks = false;

    public $FormMedia = false;

    public $FormDelete = false;

    public $FormAdd = false;

    public $FormEdit = false;

    public $FormEditBlock = false;

    public $Editorjs;

    public $data;

    public $selected = [];

    public $setting;

    public $cssClassname;

    protected $rules = [

        'post.title' => 'required|string|min:3',
        'post.meta_description' => '',
        'post.slug' => '',
        'post.layout' => '',

        'post.password' => '',
        'post.begin_at' => '',
        'post.end_at' => '',
        'blocks.*.id' => '',
        'blocks.*.name' => '',
        'fields.*.id' => '',
        'fields.*.data' => '',

    ];

    // protected $listeners = [
    //     'editorjssave' => 'saveEditorState',
    //     'refreshmedia' => 'resetPageComponent',
    // ];

    public function saveEditorState($editorJsonData, $id)
    {

        if (! empty($editorJsonData)) {

            Datafield::whereId($id)->update(['data' => $editorJsonData]);
            // foreach($itemg['items'] as $item){
            //     block::whereId($item['value'])->update(['order' => $item['order']]);
            // }
            // dump($itemg);

        }

        // $this->resetPageComponent();
    }

    public function mount($id)
    {
        $this->post = Post::findOrFail($id);

        $this->blocks = Block::where('blockable_type', 'post')->where('blockable_id', $id)->orderBy('order', 'asc')->where('subgroup', null)->with('children')->with('datafield')->get();

        $this->blocktemplates = Blocktemplates::orderBy('order', 'asc')->get()->all();

        $this->title = $this->post->title;
        $this->description = $this->post->meta_description;
        $this->layout = $this->post->layout;
        $this->status = $this->post->status;
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

    public function addBlock($blocktemplatesID, $name, $type, $grid = null, $iconclass = null)
    {

        $tempBlock = Blocktemplates::where('id', $blocktemplatesID)->first();
        $block = $this->post->blocks()->create([
            'name' => $name,
            'subgroup' => $this->blockgroupId,

            'status' => 'published',
            'iconclass' => $tempBlock->iconclass ?? $iconclass,
            'type' => $type,
            'order' => '999',
        ]);

        $blockmeta = Block::find($block->id);
        $blockmeta->saveMeta([
            'layout' => 'popout',
            'alignment' => 'left',
            'slider' => '',
        ]);

        if ($type == 'wysiwyg') {
            Datafield::create([
                'block_id' => $block->id,
                'type' => 'wysiwyg',
                'order' => '1',
            ]);
        }

        if ($blocktemplatesID != null) {
            $get_blocks = Blockfields::where('blocktemplate_id', $blocktemplatesID)->get();

            foreach ($get_blocks as $value) {
                Datafield::create([
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
        $this->dispatch('refreshComponent');
        $this->dispatch('status');
    }

    public function resetPageComponent()
    {
        $this->mount($this->post->id);

        $this->FormMedia = false;
        $this->dispatch('refreshComponent');
        $this->dispatch('status');

        // return redirect()->to('admin');
    }

    public function clone($id)
    {
        $block = block::find($id);
        $newblock = $block->replicate();

        $newblock->created_at = Carbon::now();

        $newblock->push();

        $fields = Datafield::where('block_id', $id)->get();

        $fields->each(function ($item, $key) use ($newblock): void {
            $copyitem = $item->replicate();
            $copyitem->block_id = $newblock->id;
            $copyitem->save();
        }, );

        $this->resetPageComponent();
    }

    public function selected($id)
    {
        $data = Datafield::findOrFail($id);

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

        $setblock = Block::find($id);

        if ($set == 'layout') {
            $setblock->saveMeta([
                'layout' => $status,
            ]);
        }
        if ($set == 'alignment') {
            $setblock->saveMeta([
                'alignment' => $status,
            ]);
        }
        if ($set == 'slider') {
            $setblock->saveMeta([
                'slider' => $status,
            ]);
        }

        $this->resetPageComponent();
    }

    public function updatestatus($id, $status)
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

    public function statusPost($id, $status)
    {
        if ($status == 'draft') {
            Post::where('id', $id)->update(['status' => 'draft']);
        }
        if ($status == 'published') {
            Post::where('id', $id)->update(['status' => 'published']);
        }

        $this->resetPageComponent();
    }

    public function edit($id)
    {

        $this->FormEditBlock = true;

        $this->datafield = Block::where('id', $id)->where('blockable_type', 'post')->with('datafield')->orderBy('order', 'asc')->get();
        $this->setting = Setting::query()->where('group', 'classname')->orderBy('order', 'asc')->get();
    }

    public function update($id, $publisheded = null)
    {

        $post = Post::findOrFail($id);

        // $this->getDynamicSEOData();
        // $post->addSEO();
        $this->dispatch('savedatajs');

        $validateData = $this->validate();

        $titlePostDB = Str::slug($post->title, '-', 'de');
        $slugPostDB = $post->slug;
        $titlePost = Str::slug($validateData['post']['title'], '-', 'de');
        $slugPost = $validateData['post']['slug'];

        $placeObj = new Post;

        if ($titlePost != $titlePostDB) {
            $numericalPrefix = 1;
            while (1) {
                $newSlug = $titlePost.'-'.$numericalPrefix++;
                $newSlug = Str::slug($newSlug, '-', 'de');
                $checkSlug = $placeObj->whereSlug($newSlug)->exists();
                if (! $checkSlug) {
                    $slugNameURL = $newSlug; //New Slug
                    break;
                }
            }
        } else {
            //Slug do not exists. Just use the selected Slug.
            $slugNameURL = $titlePost;
        }

        if ($publisheded == true) {
            Post::where('id', $id)->update(['status' => 'published']);
            $this->dispatch('status');
        }

        $post->update([
            'title' => $this->title,
            'meta_description' => $this->description,
            // 'layout' => $this->layout,
            'status' => $this->status,
            'slug' => $slugNameURL,
            'updated_at' => Carbon::now(),
        ]);

        $post->update([
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
                Datafield::whereId($itemg['id'])->update($itemg);
                // foreach($itemg['items'] as $item){
                //     block::whereId($item['value'])->update(['order' => $item['order']]);
                // }
                // dump($itemg);
            }
        }

        $this->resetPageComponent();
    }

    public function removemediaThumbnails($id)
    {
        Post::whereId($id)->update(['thumbnails' => null]);
        $this->resetPageComponent();
    }

    public function removemedia($id)
    {
        Datafield::whereId($id)->delete();
        $this->resetPageComponent();
    }

    public function delete() //delete block
    {
        block::destroy($this->getId);
        $this->FormDelete = false;
        // $this->mount($this->selectedItem);
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
        // Post::whereId($list['value'])->update(['order' => $list['order']]);
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
            //     Post::whereId($item['value'])->update(['order' => $item['order']]);
            // }
        }

        $this->resetPageComponent();
        $this->dispatch('status');
        // Post::whereId($list['value'])->update(['order' => $list['order']]);
    }

    //
    public function render()
    {

        return view('kompass::livewire.posts.posts-show')
            ->layout('kompass::admin.layouts.app');
    }
}
