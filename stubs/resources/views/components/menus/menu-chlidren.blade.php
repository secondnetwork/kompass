@foreach ($childrensub as $childitem )

    @if ($loop->first)
    <ul class="submenu pl-4 bg-slate-400">
    @endif

        <x-menus.menu :item="$childitem" class="itemblock shadow border-r-4  border-b-2 border-purple-500"/>

    @if ($loop->last)
    </ul>
    @endif

@endforeach


