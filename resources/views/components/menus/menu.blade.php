@props([
    'item' => '',
    'url' => '',
    'target' => '',
    'title' => '',
    'class' => '',
])

@php
    $string = $item->url;
    $explode = explode('/', $string);
    $last = end($explode);
    $active = request()->is('admin/'.$last.'') ? 'active' : '';

    // Does this item act as a dropdown group? (any child belongs to it)
    // Also detect whether a child is the current route, to auto-expand the group.
    $groups = 0;
    $childActive = false;
    foreach ($item->children as $children) {
        if ($children->subgroup) {
            $groups = 1;
        }
        $childExplode = explode('/', $children->url ?? '');
        $childLast = end($childExplode);
        if ($children->url && request()->is('admin/'.$childLast.'')) {
            $childActive = true;
        }
    }
@endphp

<li class="sidenav__list-item" x-data="{ expanded: @js((bool) $childActive) }">

    <a class="{{ $active }} flex items-center gap-2 text-sm font-semibold cursor-pointer rounded-md"
        @if ($groups)
            role="button" @click="expanded = !expanded" :aria-expanded="expanded"
        @else
            href="{{ $item->url }}" target="{{ $item->target }}" rel="noopener noreferrer"
        @endif>

        @if ($item->iconclass)
            @svg(str_starts_with($item->iconclass, 'tabler-') ? $item->iconclass : 'tabler-'.$item->iconclass, 'icon-lg shrink-0')
        @else
            <x-tabler-point class="icon-lg shrink-0 opacity-60" />
        @endif

        <span class="flex-1 truncate">{{ $item->title }}</span>

        @if ($groups)
            <span class="shrink-0 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''">
                <x-tabler-chevron-down class="size-4 stroke-[1.5] text-base-content/50" />
            </span>
        @endif
    </a>

    <x-kompass::menus.menu-chlidren :childrensub="$item['children']->sortBy('order')" />

</li>
