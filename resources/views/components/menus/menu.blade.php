@props([
    'item' => '',
    'url' => '',
    'target' => '',
    'title' => '',
    'class' => '',
])

<li class="sidenav__list-item" x-data="{ expanded: false }">
    @php

    $string = $item->url;
    $explode = explode('/', $string);
    $last = end($explode);
    $active =  request()->is('admin/'.$last.'') ? 'active' : '' ;
    $groups = 0;
        
    @endphp
    @foreach ($item->children as $children)
        @if ($children->subgroup)
            @php
                $groups = 1;
            @endphp
        @endif
    @endforeach


    <a class="{{ $active }}" @if ($groups) @click="expanded = ! expanded" @else href="{{ $item->url }}" target="{{ $item->target }}" @endif
        rel="noopener noreferrer" class="flex gap-2 text-sm font-semibold cursor-pointer">
        @if ($item->iconclass)
            @svg('tabler-' . $item->iconclass)
        @else
            @svg('tabler-pencil')
        @endif

        <span>{{ $item->title }}</span>
        @if ($groups)
            <span :class="!expanded ? '' : 'rotate-180'"
                class="transform transition-transform duration-500 absolute right-4 ">
                <x-tabler-chevron-down class="cursor-pointer stroke-current size-6 text-gray-900  stroke-[1.5]" />
            </span>
        @endif

    </a>

    <x-kompass::menus.menu-chlidren :childrensub="$item['children']->sortBy('order')" />

</li>
