@isset($menuitem)
<nav>
    <ul>
        @foreach ($menuitem as $item)
        <x-menus.menu :item="$item" />
        @endforeach
    </ul>
</nav>
@endisset




