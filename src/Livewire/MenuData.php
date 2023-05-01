<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Component;
use Secondnetwork\Kompass\Models\Menu;
use Secondnetwork\Kompass\Models\Menuitem;

class MenuData extends Component
{
    public $title;

    public $newName;

    public $menu;

    public $url;

    public $color;

    public $iconclass;
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
        'url' => 'required|string|min:1',
        'color' => '',
        'iconclass' => '',
        'target' => '',
    ];

    public function mount($id)
    {
        $this->menu_id = $id;
        $this->menu = Menu::findOrFail($id);
        $this->menuitem = Menuitem::where('menu_id', $id)->orderBy('order', 'asc')->where('subgroup', null)->with('children')->get();
    }

    public function selectItem($itemId, $action, $groupId = null)
    {
        $this->selectedItem = $itemId;
        $this->groupId = $groupId;

        if ($action == 'additem') {
            $this->FormEdit = true;
        }
        if ($action == 'update') {
            $this->updateitem($itemId);
        }
        if ($action == 'deleteblock') {
            $this->FormDelete = true;
        }
    }

    public function addNew($menuId)
    {
        $validate = $this->validate();

        Menuitem::create([
            'menu_id' => $menuId,
            'title' => $validate['title'],
            'url' => $validate['url'],
            'target' => $validate['target'],
            'color' => $validate['color'],
            'iconclass' => $validate['iconclass'],
            'subgroup' => $this->groupId,
        ]);

        $this->call_emit_reset();
    }

    public function updateitem($id)
    {

        $menuitem = Menuitem::findOrFail($id);

        $menuitem->update([
            'url' => $this->url,
            'target' => $this->target,
            'color' => $this->color,
            'iconclass' => $this->iconclass,
        ]);

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

    public function call_emit_reset()
    {
        $this->FormEdit = false;
        $this->mount($this->menu_id);
        $this->emit('refreshComponentGroup');
        $this->emit('status');
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
        // dump('sd');
    }

    public function updateItemsOrder($list)
    {
        foreach ($list as $itemgroup) {
            Menuitem::whereId($itemgroup['value'])->update(['order' => $itemgroup['order']]);

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
