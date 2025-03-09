@props([
    'item' => '',
])

@if ('group' == $item->type)

        <div
            class="group grid md:grid gap-4 transition-all ease-in-out duration-500 grid-cols-{{ $item->layoutgrid }} {{ $item->getMeta('css-classname') ?? '' }} {{ $item->getMeta('layout') ?? '' }}  {{ $item->getMeta('alignment') }}">

            @foreach ($item->children as $item)
                <x-blocks.components :item="$item" />
            @endforeach

        </div>

@endif