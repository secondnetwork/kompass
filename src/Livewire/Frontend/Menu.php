<?php

namespace Secondnetwork\Kompass\Livewire\Frontend;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Secondnetwork\Kompass\Models\Menu as Menus;
use Secondnetwork\Kompass\Models\Menuitem;

class Menu extends Component
{
    public $name;

    public $menuitem;

    public function mount($name = null)
    {
        $this->name = $name;

        $this->menu = Cache::rememberForever('kompass_menu_'.$name, function () {
            return Menus::where('slug', $this->name)->first();
        });
        if ($this->menu) {
            $this->menuitem = Cache::rememberForever('kompass_menuitem_'.$name, function () {
                return Menuitem::where('menu_id', $this->menu['id'])->orderBy('order')->where('subgroup', null)->with('children')->get();
            });
        }
    }

    public function render()
    {
        return view('livewire.menu', [$this->menuitem])->layout('layouts.main');
    }
}
