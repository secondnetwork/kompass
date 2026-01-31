<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Component;
use Secondnetwork\Kompass\Models\Menu;
use Secondnetwork\Kompass\Models\Menuitem;
use Secondnetwork\Kompass\Models\Page;
use Illuminate\Support\Facades\File;

class MenuData extends Component
{
    public $page_id = null;

    public $pages = [];

    public $iconSearch = '';

    public $selectedIcon = '';

    public $filteredIcons = [];

    private $iconPath = '';

    public function call_emit_reset()
    {
        $this->mount($this->menu->id);
        $this->dispatch('refreshComponentGroup');
        $this->dispatch('status');
    }

    public function handleSort($item, $position)
    {
        $movedItemModel = Menuitem::findOrFail($item);
        $subgroup = $movedItemModel->subgroup;

        $items = Menuitem::where('menu_id', $this->menu->id)
            ->where('subgroup', $subgroup)
            ->orderBy('order', 'asc')
            ->get();

        $movedItemIndex = $items->search(function ($menuItem) use ($item) {
            return $menuItem->id == $item;
        });

        if ($movedItemIndex === false) {
            return;
        }

        $movedItem = $items->pull($movedItemIndex);

        $items->splice($position, 0, [$movedItem]);

        foreach ($items->values() as $index => $menuItem) {
            if ($menuItem->order !== $index) {
                $menuItem->update(['order' => $index]);
            }
        }
        $this->call_emit_reset();
    }
    public $title;

    public $newName;

    public $menu;

    public $url;

    public $color;

    public $iconclass;

    public $selectedItem;

    public $item;

    public $target = '_self';

    public $groupId = null;

    public $menuitem;

    public $FormDelete = false;

    public $FormAdd = false;

    public $FormEdit = false;

    public $timestamps = false;

    protected $listeners = [
        'refreshComponentGroup' => '$refresh',
    ];

    protected $rules = [
        'title' => 'required|string|min:3',
        'url' => 'required_without:page_id|string|min:1',
        'page_id' => 'nullable|exists:pages,id',
        'color' => '',
        'iconclass' => '',
        'target' => '',
    ];

    public function mount($id)
    {
        $this->menu = Menu::findOrFail($id);
        $this->menuitem = Menuitem::where('menu_id', $id)->orderBy('order', 'asc')->where('subgroup', null)->with('children')->get();
        $this->pages = Page::orderBy('order', 'asc')->get()->map(fn($page) => ['id' => $page->id, 'name' => $page->title])->toArray();
        
        $this->loadIcons();
    }

    private function getIconPath(): string
    {
        $possiblePaths = [
            base_path('vendor/secondnetwork/blade-tabler-icons/resources/svg'),
            dirname(base_path()) . '/vendor/secondnetwork/blade-tabler-icons/resources/svg',
            public_path('vendor/blade-tabler-icons'),
        ];

        foreach ($possiblePaths as $path) {
            if (is_dir($path)) {
                return $path;
            }
        }

        return '';
    }

    public function loadIcons()
    {
        $this->filteredIcons = [];

        $iconPath = $this->getIconPath();
        
        if (empty($iconPath) || !is_dir($iconPath)) {
            return;
        }

        try {
            $files = File::files($iconPath);
            
            if (empty($files)) {
                return;
            }
            
            $icons = collect($files)
                ->map(fn($file) => str_replace('.svg', '', $file->getFilename()))
                ->sort()
                ->values();

            if ($this->iconSearch) {
                $search = strtolower(trim($this->iconSearch));
                if (!empty($search)) {
                    $icons = $icons->filter(fn($name) =>
                        str_contains(strtolower($name), $search)
                    );
                }
            }

            $this->filteredIcons = $icons->take(100)->map(fn($name) => [
                'id' => 'tabler-' . $name,
                'name' => $name,
                'full_name' => 'tabler-' . $name,
            ])->values()->toArray();
        } catch (\Exception $e) {
            $this->filteredIcons = [];
        }
    }

    public function updatedIconSearch()
    {
        $this->loadIcons();
    }

    public function selectIcon($name)
    {
        $this->selectedIcon = 'tabler-' . $name;
        $this->iconclass = 'tabler-' . $name;
    }

    public function resetIcon()
    {
        $this->selectedIcon = '';
        $this->iconclass = '';
    }

    public function selectItem($itemId, $action, $groupId = null)
    {
        $this->selectedItem = $itemId;
        $this->groupId = $groupId;

        if ($action == 'additem') {
            $this->title = '';
            $this->url = '';
            $this->target = '_self';
            $this->color = '';
            $this->iconclass = '';
            $this->selectedIcon = '';
            $this->page_id = null;
            $this->selectedItem = false;
            $this->FormEdit = true;
        }
        if ($action == 'update') {
            $model = Menuitem::findOrFail($itemId);
            $this->title = $model->title;
            $this->url = $model->url;
            $this->target = $model->target;
            $this->color = $model->color;
            $this->iconclass = $model->iconclass;
            $this->selectedIcon = $model->iconclass;
            $this->page_id = $model->page_id;
            $this->FormEdit = true;
        }
        if ($action == 'deleteblock') {
            $this->FormDelete = true;
        }
    }

    public function addNew()
    {
        $validate = $this->validate();

        Menuitem::updateOrCreate([
            'id' => $this->selectedItem,
        ], [
            'menu_id' => $this->menu->id,
            'title' => $this->title,
            'url' => $this->url,
            'page_id' => $this->page_id,
            'target' => $this->target,
            'color' => $this->color,
            'iconclass' => $this->iconclass,
            'subgroup' => $this->groupId,
        ]);
        $this->FormEdit = false;
        $this->call_emit_reset();
    }

    public function savename($id)
    {
        if ($this->newName != null) {
            $block = Menuitem::findOrFail($id);
            $block->update(['title' => $this->newName]);
        }
        $this->call_emit_reset();
    }

    public function render()
    {
        return view('kompass::livewire.menus.menus-show')
            ->layout('kompass::admin.layouts.app');
    }

    public function updatedPageId($value)
    {
        if ($value) {
            $page = Page::find($value);
            if ($page && $page->slug) {
                $this->url = '/' . $page->slug;
            }
        }
    }


    public function delete()
    {
        Menuitem::find($this->selectedItem)->delete();
        $this->FormDelete = false;
        $this->call_emit_reset();
    }

    public function updateGroupOrder($list)
    {

        foreach ($list as $item) {
            Menuitem::whereId($item['value'])->update(['order' => $item['order']]);
        }
        $this->call_emit_reset();
    }

    public function updateItemsOrder($list)
    {

        foreach ($list as $itemgroup) {

            // Menuitem::whereId($itemgroup['value'])->update(['order' => $itemgroup['order']]);

            if ($itemgroup['items']) {
                foreach ($itemgroup['items'] as $item) {
                    if ($item['value'] != $itemgroup['value']) {
                        Menuitem::whereId($item['value'])->update(['order' => $item['order'], 'subgroup' => $itemgroup['value']]);
                    }
                }
            }
        }
        $this->call_emit_reset();
    }
}
