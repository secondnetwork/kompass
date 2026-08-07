@props(['childrensub' => [], 'level' => 1])
@foreach ($childrensub as $childitem)

    @if ($loop->first)
    <ul class="menu bg-base-100 text-base-content rounded-box border border-base-200 shadow-lg min-w-48 z-50">
    @endif

        <x-menus.menus :menuitem="[$childitem]" :level="$level" />

    @if ($loop->last)
    </ul>
    @endif

@endforeach
