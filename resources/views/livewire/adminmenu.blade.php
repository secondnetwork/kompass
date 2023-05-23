@isset($menuitem)

        @foreach ($menuitem as $item)
        <x-kompass::menus.menu :item="$item" />
        @endforeach

@endisset




