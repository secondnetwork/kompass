@props([
    'item' => '',
    'url' => '',
    'target' => '',
    'title' => '',
])

<li class="sidenav__list-item">

    <a href="{{$item->url}}" target="{{$item->target}}" rel="noopener noreferrer">
        @svg('tabler-'.$item->iconclass)
        <span>{{$item->title}}</span>
    </a>

        <x-kompass::menus.menu-chlidren :childrensub="$item['children']->sortBy('order')"/>

</li>
