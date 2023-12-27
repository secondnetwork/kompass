@props([
    'item' => '',
    'url' => '',
    'target' => '',
    'title' => '',
])

<li x-data="{ open: false }"  @mouseover.away = "open = false" class="relative z-10 flex items-center transition-all">

    <a @click="open = true" @mouseover="open = true" wire:navigate
    href="{{$item->url}}"
        target="{{$item->target}}"
        rel="noopener noreferrer">{{$item->title}}</a>
        @if ($item['children']->count())
            <x-tabler-chevron-down/>
        @endif



        <x-menus.menu-chlidren :childrensub="$item['children']->sortBy('order')"/>

</li>

    {{-- icon_class color --}}



