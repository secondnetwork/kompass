@props([
    'item' => '',
])

@if ('group' == $item->type)
    @php
        $layoutgrid = $item->layoutgrid ?? 12;
        $colSpan = $item->layoutgrid ? 'md:col-span-' . $item->layoutgrid : '';
        $gridCols = 'md:grid-cols-' . $layoutgrid;
    @endphp

    <div class="group md:grid gap-6 transition-all ease-in-out duration-500 {{ $gridCols }} {{ $colSpan }} {{ get_meta($item, 'css-classname', '') }} {{ get_meta($item, 'layout', '') }} {{ get_meta($item, 'alignment', '') }}">
        @foreach ($item->children as $child)
            <x-blocks.components :item="$child" />
        @endforeach
    </div>
@endif
