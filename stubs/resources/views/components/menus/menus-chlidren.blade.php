@props(['childrensub' => [], 'level' => 1])
@foreach ($childrensub as $childitem )

    @if ($loop->first)
    <ul class="submenu absolute left-0 top-full z-50 bg-white mt-0 p-1 min-w-48 shadow-lg rounded-lg border border-gray-100">
    @endif

        <x-menus.menus :menuitem="[$childitem]" :level="$level"/>

    @if ($loop->last)
    </ul>
    @endif

@endforeach


