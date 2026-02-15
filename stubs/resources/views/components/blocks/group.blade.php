@props([
    'item' => '',
])

@if ('group' == $item->type)
    @php
        $layoutgrid = $item->layoutgrid ?? 12;
        $colSpan = $item->layoutgrid ? 'col-span-' . $item->layoutgrid : '';
        $gridCols = 'grid-cols-' . $layoutgrid;
    @endphp

        <div
            class="group md:grid gap-6 transition-all ease-in-out duration-500 {{ $gridCols }} {{ $colSpan }} {{ $item->getMeta('css-classname') ?? '' }} {{ $item->getMeta('layout') ?? '' }} {{ $item->getMeta('alignment') }}">

            @foreach ($item->children as $item)
                <x-blocks.components :item="$item" />
            @endforeach

        </div>

@endif