@props([
    'item' => '',
])

@if ('group' == $item->type)
    @php
        $layoutgrid = $item->layoutgrid ?? 12;
        $colSpan = $item->layoutgrid ? 'md:col-span-' . $item->layoutgrid : '';
        $gridCols = 'md:grid-cols-' . $layoutgrid;
        $order = $item->getMeta('order') ?? '';
        $orderClasses = match($order) {
            'reverse' => 'flex flex-col-reverse',
            default => '',
        };
        $align = $item->getMeta('align') ?? '';
    @endphp
    <div {{ $attributes->merge(['class' => 'group gap-6 md:grid ' . $align . ' ' . $orderClasses . ' ' . $gridCols . ' ' . $colSpan . ' ' . get_meta($item, 'css-classname', '') . ' ' . get_meta($item, 'layout', '') . ' ' . get_meta($item, 'alignment', '') ]) }}>
        @foreach ($item->children as $child)
            <x-blocks.components :item="$child" />
        @endforeach
    </div>
@endif
