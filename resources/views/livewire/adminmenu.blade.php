@isset($menuitem)

        <ul>
        @foreach ($menuitem as $item)

                <x-kompass::menus.menu :item="$item" />
        @endforeach
        </ul>

@endisset




