<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Component;
use Secondnetwork\Kompass\Models\Menu as Menus;
use Secondnetwork\Kompass\Models\Menuitem;

class Menu extends Component
{
    public $name;

    public $menu;

    public $menuitem = [];

    public function mount($name = null)
    {
        $this->name = $name;

        $this->menu = Menus::where('group', $this->name)->first();
        if ($this->menu) {
            $this->menuitem = Menuitem::where('menu_id', $this->menu['id'])->orderBy('order')->where('subgroup', null)->with('children')->get();
        }

        return '';
    }

    public function render()
    {
        return view('kompass::components.menus.adminmenu', [$this->menuitem])->layout('layouts.app');
    }
}
