@props([
    'item' => '',
])

@php
    ['gridCols' => $gridCols, 'colSpan' => $colSpan] = block_grid_classes($item);
    $order = $item->getMeta('order') ?? '';
    $orderClasses = match ($order) {
        'reverse' => 'flex flex-col-reverse',
        default => '',
    };
    $align = $item->getMeta('align') ?? '';
@endphp
<div
    {{ $attributes->merge(['class' => 'group gap-6 md:grid ' . $align . ' ' . $orderClasses . ' ' . $gridCols . ' ' . $colSpan . ' ' . get_meta($item, 'css-classname', '') . ' ' . get_meta($item, 'layout', '') . ' ' . get_meta($item, 'alignment', '')]) }}>
    @foreach ($item->children as $child)
        <x-blocks.components :item="$child" />
    @endforeach
</div>
