<?php

namespace Secondnetwork\Kompass\Livewire\Frontend;

use Illuminate\Support\Facades\Cache;
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
        $locale = app()->getLocale();
        $cacheKey = 'kompass_menu_'.$name.'_'.$locale;

        $this->menu = Cache::rememberForever($cacheKey, function () use ($locale) {
            $isMultilingual = setting('global.multilingual');
            
            $menu = null;
            if ($isMultilingual) {
                $menu = Menus::where('slug', $this->name)->where('land', $locale)->first();
            }
            
            // Fallback to non-language specific menu or first matching slug
            if (!$menu) {
                $menu = Menus::where('slug', $this->name)->first();
            }
            
            return $menu;
        });

        if ($this->menu) {
            $this->menuitem = Cache::rememberForever('kompass_menuitem_'.$this->menu['id'], function () {
                return Menuitem::where('menu_id', $this->menu['id'])->orderBy('order')->where('subgroup', null)->with('children')->get();
            });
        }
    }

    public function render()
    {
        return view('livewire.menu', [$this->menuitem])->layout('layouts.main');
    }
}
