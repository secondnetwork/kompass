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
    public $selectedItem;
    public $timestamps = false;
    public $FormDelete = false;
    public $FormAdd = false;
    public $FormEdit = false;

    protected $rules = ['name' => ''];

    public function call_emit_reset()
    {
        $this->dispatch('status');
    }

    protected function headerTable(): array
    {
        return ['', 'Name', ''];
    }

    protected function dataTable(): array
    {
        return ['name'];
    }

    public function mount()
    {
        $this->headers = $this->headerTable();
        $this->data = $this->dataTable();
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;
        if ($action == 'add') $this->FormAdd = true;
        if ($action == 'delete') $this->FormDelete = true;
    }

    public function addMenu()
    {
        $this->validate();
        $menu = Menu::create(['name' => $this->name, 'group' => $this->group]);
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
        return Menu::orderBy('order', 'ASC')->get();
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
}
