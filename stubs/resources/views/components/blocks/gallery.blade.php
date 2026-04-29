@props([
    'item' => '',
])

@use('Secondnetwork\Kompass\Models\File', 'Files')

@php
    ['gridCols' => $gridCols, 'colSpan' => $colSpan] = block_grid_classes($item);
@endphp
<div {{ $attributes->merge(['class' => 'relative group ' . $gridCols . ' ' . $colSpan]) }}>
    <div class="md:grid gap-4 transition-all ease-in-out duration-500 grid-cols-{{ $item->grid }} one-image {{ get_meta($item, 'css-classname') }}">
        @foreach ($item->datafield as $image)
            <x-image :id="$image['data']" wire:key="gallery-{{ $item->id }}-{{ $loop->index }}" class="w-full h-full rounded-lg" />
        @endforeach
    </div>
</div>
