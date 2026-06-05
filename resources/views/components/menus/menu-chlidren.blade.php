@if ($childrensub->isNotEmpty())
    <ul x-show="expanded" x-collapse x-cloak class="submenu">
        @foreach ($childrensub as $childitem)
            <x-kompass::menus.menu :item="$childitem" />
        @endforeach
    </ul>
@endif
