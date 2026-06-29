@props([
    'item' => '',
])

@php
    ['gridCols' => $gridCols, 'colSpan' => $colSpan] = block_grid_classes($item);

    $galleryField = $item->datafield->firstWhere('type', 'gallery');

    if (is_array($galleryField?->data)) {
        // New model: single row, data = [id, id, ...]
        $galleryImages = $galleryField->data;
    } else {
        // Legacy model: multiple rows, each with a single integer in data
        $galleryImages = $item->datafield
            ->where('type', 'gallery')
            ->filter(fn ($d) => !empty($d->data) && !is_array($d->data))
            ->pluck('data')
            ->toArray();
    }
@endphp
<div {{ $attributes->merge(['class' => 'relative group ' . $gridCols . ' ' . $colSpan]) }}>
    <div class="md:grid gap-4 transition-all ease-in-out duration-500 grid-cols-{{ $item->grid }} one-image {{ get_meta($item, 'css-classname') }}">
        @foreach ($galleryImages as $imageId)
            <x-media-item :id="$imageId" wire:key="gallery-{{ $item->id }}-{{ $loop->index }}" class="w-full h-full rounded-lg" />
        @endforeach
    </div>
</div>
