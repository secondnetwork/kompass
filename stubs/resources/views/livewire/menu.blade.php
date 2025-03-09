@isset($menuitem)

    <ul>
        @foreach ($menuitem as $item)
        <x-menus.menu :item="$item" />
        @endforeach
    </ul>

@endisset




