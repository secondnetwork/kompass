@isset($menuitem)
<div>
        <ul>
        @foreach ($menuitem as $item)
                <x-kompass::menus.menu :item="$item" />
        @endforeach
        </ul>
</div>
@endisset




