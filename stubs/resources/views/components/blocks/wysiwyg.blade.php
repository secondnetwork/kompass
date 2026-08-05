@props([
    'item' => '',
    'field' => null,
])

@php
    $renderBlocks = wysiwyg_blocks($item, $field);
@endphp

@if ($field || is_object($item))
    @php
        $linkUrl = get_meta($item, 'link-url');
        $alignment = get_meta($item, 'alignment');
        $cssclassname = get_meta($item, 'css-classname', '');
        $alignmentClass = match ($alignment) {
            'align-left' => 'text-left',
            'align-center' => 'text-center',
            'align-right' => 'text-right',
            default => '',
        };
        ['gridCols' => $gridCols, 'colSpan' => $colSpan] = block_grid_classes($item);
    @endphp

    <div {{ $attributes->merge(['class' => "relative group {$cssclassname} {$alignmentClass} {$gridCols} {$colSpan}"]) }}>
        @if ($linkUrl)
            <a href="{{ $linkUrl }}" class="block absolute inset-0 z-10"></a>
            <div class="group-hover:bg-primary/60 transition block absolute inset-0 rounded-2xl -z-10"></div>
        @endif

        <x-blocks.wysiwyg-content :blocks="$renderBlocks" />
    </div>
@endif
