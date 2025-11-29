@foreach ($childrensub as $childitem )

    @if ($loop->first)
    <ul class="submenu pl-4 absolute left-0 z-50  bg-white top-6 p-4
     shadow-lg rounded overflow-hidden " x-cloak x-show.transition="open" @click.away="open = false" @keydown.escape.window="open = false"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
    @endif

        <x-menu.menu :item="$childitem" class="itemblock shadow border-r-4  border-b-2 border-purple-500"/>

    @if ($loop->last)
    </ul>
    @endif

@endforeach


