@foreach ($childrensub as $childitem)
    @if ($loop->first)
        <div x-show="expanded" x-collapse>
            <ul class="submenu pl-4 shadow-inner  bg-gray-200">
    @endif

    <x-kompass::menus.menu :item="$childitem" />
    @if ($loop->last)
        </ul>
    @endif
    </div>
@endforeach
