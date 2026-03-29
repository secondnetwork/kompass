@props([
    'item' => '',
])

@if ('group' == $item->type)
    @php
        $layoutgrid = $item->layoutgrid ?? 12;
        $colSpan = $item->layoutgrid ? 'md:col-span-' . $item->layoutgrid : '';
        $gridCols = 'md:grid-cols-' . $layoutgrid;
    @endphp

        <div
            class="grid gap-6 transition-all ease-in-out duration-500  {{ $gridCols }} {{ $colSpan }}  {{ $item->getMeta('alignment') }}"
            >

            @foreach ($item->children as $item)
                <x-blocks.components :item="$item" />
            @endforeach

        </div>

@endif