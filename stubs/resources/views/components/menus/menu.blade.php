@props([
    'item' => '',
    'url' => '',
    'target' => '',
    'title' => '',
])

<li>
    <a href="{{$item->url}}" target="{{$item->target}}" rel="noopener noreferrer">{{$item->title}}</a>

        <x-menus.menu-chlidren :childrensub="$item['children']->sortBy('order')"/>

</li>

    {{-- icon_class color --}}


