@foreach ($childrensub as $childitem )

    @if ($loop->first)
 

                <div x-show="expanded" x-collapse>
    <ul class="submenu pl-4 shadow  bg-slate-50">
    @endif

        <x-kompass::menus.menu :item="$childitem" />
    @if ($loop->last)
    </ul>
    @endif

@endforeach


