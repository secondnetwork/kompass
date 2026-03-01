<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Component;
use Secondnetwork\Kompass\Models\Menu;

class MenuTable extends Component
{
    public $name;
    public $group;
    public $headers;
    public $data;
    public $newName;
    public $land = '';
    public $available_locales;
    public $selectedItem;
    public $timestamps = false;
    public $FormDelete = false;
    public $FormAdd = false;
    public $FormClone = false;
    public $FormEdit = false;
    public $cloneLand = '';
    public $orderBy = 'order';
    public $orderAsc = true;

    protected $rules = ['name' => ''];

    public function updatedLand($value)
    {
        session(['kompass_last_land' => $value]);
    }

    public function call_emit_reset()
    {
        $this->dispatch('status');
    }

    protected function headerTable(): array
    {
        $headers = ['', 'Name'];
        if (setting('global.multilingual')) {
            $headers[] = 'land';
        }
        $headers[] = '';
        return $headers;
    }

    protected function dataTable(): array
    {
        $data = ['name'];
        if (setting('global.multilingual')) {
            $data[] = 'land';
        }
        return $data;
    }

    public function mount()
    {
        $this->headers = $this->headerTable();
        $this->data = $this->dataTable();

        $localesData = setting('global.available_locales');
        if ($localesData) {
            $locales = is_array($localesData) ? $localesData : json_decode($localesData, true);
        } else {
            $locales = ['de', 'en', 'tr'];
        }

        $appLocale = config('app.locale', 'de');
        
        // Move app locale to front
        if (($key = array_search($appLocale, $locales)) !== false) {
            unset($locales[$key]);
            array_unshift($locales, $appLocale);
        }
        
        $this->available_locales = $locales;
        
        if (empty($this->land) && session()->has('kompass_last_land')) {
            $this->land = session('kompass_last_land');
        }
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;
        if ($action == 'add') $this->FormAdd = true;
        if ($action == 'delete') $this->FormDelete = true;
        if ($action == 'clone') {
            $this->FormClone = true;
            $this->cloneLand = Menu::find($itemId)->land ?? config('app.locale', 'de');
        }
    }

    public function cloneMenu()
    {
        $id = $this->selectedItem;
        $originalMenu = Menu::findOrFail($id);
        
        $newMenu = $originalMenu->replicate();
        $newMenu->name = $originalMenu->name . ' (copy)';
        $newMenu->land = $this->cloneLand;
        $newMenu->push();

        $items = \Secondnetwork\Kompass\Models\Menuitem::where('menu_id', $id)->whereNull('subgroup')->get();
        
        foreach ($items as $item) {
            $newItem = $item->replicate();
            $newItem->menu_id = $newMenu->id;
            $newItem->push();

            // Handle children
            $children = \Secondnetwork\Kompass\Models\Menuitem::where('subgroup', $item->id)->get();
            foreach ($children as $child) {
                $newChild = $child->replicate();
                $newChild->menu_id = $newMenu->id;
                $newChild->subgroup = $newItem->id;
                $newChild->push();
            }
        }

        $this->FormClone = false;
        return redirect()->to('/admin/menus/show/'.$newMenu->id);
    }

    public function addMenu()
    {
        $this->validate();
        $menu = Menu::create([
            'name' => $this->name, 
            'group' => $this->group,
            'land' => $this->land ?: config('app.locale', 'de'),
        ]);
        $this->FormAdd = false;
        return redirect()->to('/admin/menus/show/'.$menu->id);
    }

    public function delete()
    {
        Menu::find($this->selectedItem)->delete();
        $this->FormDelete = false;
    }

    private function resultDate()
    {
        $query = Menu::query();

        if (setting('global.multilingual') && $this->land) {
            $query->where('land', $this->land);
        }

        return $query->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->get();
    }

    public function rename($id)
    {
        if ($this->newName != null) {
            $block = Menu::findOrFail($id);
            $block->update(['name' => $this->newName]);
        }
    }

    public function render()
    {
        return view('kompass::livewire.menus.menus-table', ['menus' => $this->resultDate()])->layout('kompass::admin.layouts.app');
    }

    public function handleSort($item, $position)
    {
        $menus = Menu::orderBy('order', 'ASC')->get();
        $movedItemIndex = $menus->search(fn ($menu) => $menu->id == $item);
        if ($movedItemIndex === false) return;
        $movedItem = $menus->pull($movedItemIndex);
        $menus->splice($position, 0, [$movedItem]);
        foreach ($menus->values() as $index => $menu) {
            if ($menu->order !== $index) $menu->update(['order' => $index]);
        }
        $this->call_emit_reset();
    }

    public function updateMenusOrder($list)
    {
        foreach ($list as $item) {
            Menu::whereId($item['value'])->update(['order' => $item['order']]);
        }
    }

    public function sortBy($field)
    {
        if ($this->orderBy === $field) {
            $this->orderAsc = !$this->orderAsc;
        } else {
            $this->orderBy = $field;
            $this->orderAsc = true;
        }
    }
}
