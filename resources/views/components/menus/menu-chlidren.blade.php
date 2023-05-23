@foreach ($childrensub as $childitem )

    @if ($loop->first)
    <ul class="submenu">
    @endif

        <x-kompass::menus.menu :item="$childitem" class="itemblock shadow border-r-4  border-b-2 border-purple-500"/>

    @if ($loop->last)
    </ul>
    @endif

@endforeach


