@props([
    'item' => '',
    'url' => '',
    'target' => '',
    'title' => '',
])

<li class="sidenav__list-item ">

    <a href="{{$item->url}}" target="{{$item->target}}" rel="noopener noreferrer" class="flex gap-2 my-4 text-sm font-semibold">
        @if ($item->iconclass)
            @svg('tabler-'.$item->iconclass)
        @endif
        <span>{{$item->title}}</span>
    </a>

        {{-- <x-kompass::menus.menu-chlidren :childrensub="$item['children']->sortBy('order')"/> --}}

</li>
