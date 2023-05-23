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
        $this->menu = Menu::findOrFail($id);
        $this->menuitem = Menuitem::where('menu_id', $id)->orderBy('order', 'asc')->where('subgroup', null)->with('children')->get();
    }

    public function selectItem($itemId, $action, $groupId = null)
    {
        $this->selectedItem = $itemId;
        $this->groupId = $groupId;

        if ($action == 'additem') {
            $this->title = '';
            $this->url = '';
            $this->target = '';
            $this->color = '';
            $this->iconclass = '';
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

    public function call_emit_reset()
    {
        $this->mount($this->menu->id);
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
