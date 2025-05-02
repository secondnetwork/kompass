<?php

namespace Secondnetwork\Kompass\Livewire;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Kolossal\Multiplex\Meta;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Models\Block;
use Secondnetwork\Kompass\Models\Blocktemplates;
use Secondnetwork\Kompass\Models\Datafield;
use Secondnetwork\Kompass\Models\Page;
use Secondnetwork\Kompass\Models\Setting;

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

    public Page $page;

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

    public $FormMediaVideo = false;

    public $FormMedia = false;

    public $FormDelete = false;

    public $FormAdd = false;

    public $FormEdit = false;

    public $FormEditBlock = false;

    public $Editorjs;

    public $oembedUrl;

    public $data = [];

    public array $selected = [];

    public $setting;

    public $cssClassname;

    protected $listeners = ['reload-pages-data' => 'reloadMount', 'component:refresh' => '$refresh'];

    protected $rules = [

        'title' => 'required|string|min:3',
        'page.meta_description' => 'nullable|string',
        'page.slug' => 'nullable|string',
        'page.layout' => 'nullable|string',
        'page.status' => 'nullable|string',

    ];

    public function mount($id)
    {
        $this->page = Page::with(['blocks.children'])->findOrFail($id); // Eager load related data
        $this->blocks = $this->page->blocks
            ->where('blockable_type', 'page')
            ->where('subgroup', null)
            ->sortBy('order');
        $this->title = $this->page->title;
        $this->description = $this->page->meta_description;
        $this->layout = $this->page->layout;
        $this->status = $this->page->status;

        $this->cssClassname = Meta::published()
            ->where('key', 'css-classname')
            ->get();

        $this->blocktemplates = Blocktemplates::orderBy('order')->get();
    }

    public function reloadMount()
    {
        $this->mount($this->page->id);
        $this->resetPageComponent();
    }

    public function selectitem($action, $itemId, $fieldOrPageName = null, $blockgroupId = null)
    {
        $this->getId = $itemId;

        match ($action) {
            'addBlock' => $this->setAddBlock($blockgroupId),
            'addMedia' => $this->setAddMedia($fieldOrPageName, $blockgroupId),
            'deleteblock' => $this->FormDelete = true,
            default => null,
        };
    }

    private function setAddBlock($blockgroupId): void
    {
        $this->FormBlocks = true;
        $this->blockgroupId = $blockgroupId;
    }

    private function setAddMedia($fieldOrPageName, $blockgroupId): void
    {
        $this->FormMedia = true;
        $this->dispatch('getIdField_changnd', $this->getId, $fieldOrPageName);
        $this->dispatch('getIdBlock', $blockgroupId);
    }

    #[on('FormMedia')]
    public function FormMedia()
    {
        $this->FormMedia = true;
    }

    public function addoEmbed($blockId)
    {
        Datafield::create([
            'block_id' => $blockId,
            'type' => 'oembed',
            'data' => $this->oembedUrl,
            'order' => 1,
        ]);

        $this->handleVideoEmbed($this->oembedUrl);
        $this->resetPageComponent();
    }

    private function handleVideoEmbed(string $url): void
    {
        $videoEmbed = videoEmbed($url);
        if ($videoEmbed['type'] == 'youtube') {
            $thumbnailName = $videoEmbed['id'].'.jpg';
            $thumbnailUrl = 'https://i.ytimg.com/vi/'.$videoEmbed['id'].'/maxresdefault.jpg';

            if (Storage::disk('public')->missing('thumbnails-video/'.$thumbnailName)) {
                $thumbnailContents = file_get_contents($thumbnailUrl);
                if ($thumbnailContents) {
                    $manager = new ImageManager(new Driver);
                    $image = $manager->read($thumbnailContents);

                    Storage::disk('public')->put('thumbnails-video/'.$thumbnailName, $image->toJpeg(60));
                }

            }
        }
    }

    public function addBlock($blocktemplatesID, $name, $type, $iconclass = null)
    {
        $tempBlock = Blocktemplates::find($blocktemplatesID);

        $block = $this->page->blocks()->create([
            'name' => $name,
            'subgroup' => $this->blockgroupId,
            'status' => 'published',
            'grid' => $tempBlock->grid ?? '1',
            'iconclass' => $tempBlock->iconclass ?? $iconclass,
            'type' => $type,
            'order' => '999',
        ]);

        $this->initializeDataFields($block->id, $type);

        $this->FormBlocks = false;
        $this->resetPageComponent();
    }

    private function initializeDataFields($blockId, string $type): void
    {
        $fieldDefinitions = match ($type) {
            'wysiwyg' => [['type' => 'wysiwyg', 'order' => '1']],
            'anchormenu' => [['name' => 'Name Anchormenu', 'type' => 'text', 'order' => '1']],
            'button' => [
                ['name' => 'Text', 'type' => 'text', 'order' => '1'],
                ['name' => 'URL', 'type' => 'text_url', 'order' => '1'],
                ['name' => 'iconclass', 'type' => 'icon', 'order' => '1'],
            ],
            default => [],
        };

        foreach ($fieldDefinitions as $definition) {
            $definition['block_id'] = $blockId;
            Datafield::create($definition);
        }
    }

    public function refreshmedia()
    {
        $this->dispatch('status');
    }

    #[on('refreshmedia')]
    public function resetPageComponent()
    {

        $this->mount($this->page->id);
        $this->FormMedia = false;
        // $this->FormEditBlock = false;
        $this->FormEdit = false;
        $this->FormBlocks = false;
        $this->dispatch('status');
        $this->dispatch('component:refresh');
        // return redirect()->to('admin');
    }

    public function clone($id)
    {
        $block = Block::findOrFail($id);
        $newBlock = $block->replicate();
        $newBlock->created_at = now();
        $newBlock->save();

        $fields = Datafield::where('block_id', $id)->get();

        foreach ($fields as $field) {
            $newField = $field->replicate();
            $newField->block_id = $newBlock->id;
            $newField->save();
        }

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

    public function savename($blockId)
    {
        if ($this->newName) {
            Block::whereId($blockId)->update(['name' => $this->newName]);
        }
        $this->resetPageComponent();
    }

    public function updateLayoutGrid($blockId, $grid)
    {
        Block::whereId($blockId)->update(['layoutgrid' => $grid]);
        $this->resetPageComponent();
    }

    public function updateGrid($id, $grid)
    {
        $setblock = Block::findOrFail($id);
        $setblock->update(['grid' => $grid]);
        $this->resetPageComponent();
    }

    public function setnewName($value)
    {
        $this->newName = $value;
    }

    private function updateBlockMeta(int $id, string $metaKey, $metaValue): void
    {
        if (! empty($metaValue)) {
            $block = Block::findOrFail($id); // Use findOrFail to handle missing Blocks
            $block->deleteMeta($metaKey);
            $block->saveMeta([$metaKey => $metaValue]);
        }
        $this->resetPageComponent();
    }

    public function classname($id)
    {
        $this->updateBlockMeta($id, 'css-classname', $this->newName);
    }

    public function idanchor($id)
    {
        $this->updateBlockMeta($id, 'id-anchor', $this->newName);
    }

    public function saveset(int $id, string $set, $status): void
    {
        $metaKeyMap = [
            'layout' => 'layout',
            'id-anchor' => 'id-anchor',
            'css-classname' => 'classname',
            'col-span' => 'col-span',
            'alignment' => 'alignment',
            'slider' => 'slider',
        ];

        if (isset($metaKeyMap[$set])) {
            $metaKey = $metaKeyMap[$set];
            $this->updateBlockMeta($id, $metaKey, $status);
        } else {
            $this->resetPageComponent(); // Or you could handle this as error
        }

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

    public function edit($id)
    {

        $this->FormEditBlock = true;

        $this->datafield = Block::where('id', $id)->where('blockable_type', 'page')->with('datafield')->orderBy('order', 'asc')->get();
        $this->setting = Setting::query()->where('group', 'classname')->orderBy('order', 'asc')->get();
    }

    // public function updating($property, $value)
    // {
    //     dump($property);
    //     dump($value);
    //     dump($this->getId);
    // }

    public function update($pageId, $publishIfNeeded = false)
    {
        $this->validate();
        $this->handlePageUpdate($pageId, $publishIfNeeded);
        $this->resetPageComponent();
    }

    private function handlePageUpdate($pageId, $publishIfNeeded)
    {
        $page = Page::findOrFail($pageId);
        $this->dispatch('saveTheDatafield');
        $this->dispatch('savedatajs');
        $slugNameURL = genSlug($page->title, $page->slug, Page::class);

        $page->update([
            'title' => $this->title,
            'meta_description' => $this->description,
            'layout' => $this->layout,
            'status' => $this->status,
            'slug' => $slugNameURL,
            'updated_at' => Carbon::now(),
        ]);

        if ($publishIfNeeded) {
            $page->update(['status' => 'published']);
            $this->dispatch('status');
        }
    }

    public function removemedia($id)
    {
        Datafield::whereId($id)->delete();
        $this->resetPageComponent();
    }

    public function delete() //delete block
    {
        Datafield::where('block_id', $this->getId)->delete();
        block::destroy($this->getId);
        $this->FormDelete = false;
        $this->resetPageComponent();
    }

    public function updateOrderImages($list)
    {

        foreach ($list as $item) {

            Datafield::whereId($item['value'])->update(['order' => $item['order']]);

        }

        $this->resetPageComponent();
        // $this->dispatch('status');
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
        // Page::whereId($list['value'])->update(['order' => $list['order']]);
    }

    //
    public function render()
    {

        return view('kompass::livewire.pages.pages-show')
            ->layout('kompass::admin.layouts.app');
    }
}
