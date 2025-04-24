@props([
    'item' => '',
    'url' => '',
    'target' => '',
    'title' => '',
])

<li x-data="{ open: false }"  @mouseover.away = "open = false" class="relative z-50 flex items-center transition-all">


    <a @if ($item->iconclass) class="{{ 'tabler-'.$item->iconclass }}" @endif @click="open = true" @mouseover="open = true"
    {{-- wire:navigate --}}
    href="{{$item->url}}" target="{{$item->target}}"
    rel="noopener noreferrer">

        @if ($item->iconclass)
        @svg('tabler-'.$item->iconclass)
        @endif

        {{$item->title}}</a>
        @if ($item['children']->count())
            <x-tabler-chevron-down/>
            <x-menus.menu-chlidren :childrensub="$item['children']->sortBy('order')"/>
        @endif

</li>

    {{-- icon_class color --}}



