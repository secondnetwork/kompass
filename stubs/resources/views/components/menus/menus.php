<?php

use Livewire\Component;
use Secondnetwork\Kompass\Models\Menu as Menus;
use Secondnetwork\Kompass\Models\Menuitem;

new class extends Component
{
    public $name;

    public $menu;

    public $menuitem = [];

    public $class = '';

    public $horizontal = null;

    public function mount($name = null, $class = '', $horizontal = null)
    {
        $this->name = $name;
        $this->class = $class;
        $this->horizontal = $horizontal;
        $this->menu = Menus::where('slug', $this->name)->first();
        if ($this->menu) {
            $this->menuitem = Menuitem::where('menu_id', $this->menu['id'])->orderBy('order')->where('subgroup', null)->with('children')->get();
        }
    }

    // public function render()
    // {
    //     return view('livewire.menu', [$this->menuitem])->layout('layouts.main');
    // }
};
