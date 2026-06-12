@props([
    'item' => '',
    'field' => null,
])

@php
    $renderBlocks = wysiwyg_blocks($item, $field);

    // type → [tag, class]. List is handled separately because of nested items.
    $tagMap = [
        'p'          => ['p',          'text-base text-base-content leading-relaxed mb-2'],
        'h1'         => ['h1',         'text-3xl font-bold tracking-tight text-base-content mb-2'],
        'h2'         => ['h2',         'text-2xl font-bold tracking-tight text-base-content mb-2'],
        'h3'         => ['h3',         'text-xl font-semibold text-base-content mb-1'],
        'h4'         => ['h4',         'text-lg font-semibold text-base-content mb-1'],
        'h5'         => ['h5',         'text-base font-semibold text-base-content mb-1'],
        'h6'         => ['h6',         'text-sm font-semibold text-base-content/60 tracking-wide mb-1'],
        'subtitle'   => ['p',          'text-sm font-medium text-base-content/60 uppercase leading-relaxed mb-4'],
        'preline'    => ['p',          'text-xs font-bold uppercase mb-1'],
        'blockquote' => ['blockquote', 'border-l-4 border-base-300 pl-4 italic text-base-content/60 my-2'],
    ];
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

        @php
            $alignMap = ['left' => 'text-left', 'center' => 'text-center', 'right' => 'text-right'];
        @endphp
        @foreach ($renderBlocks as $block)
            @php $alignCls = $alignMap[$block['alignment'] ?? ''] ?? ''; @endphp
            @if (($block['type'] ?? null) === 'list')
                @php $tag = ($block['data']['type'] ?? 'unordered') === 'ordered' ? 'ol' : 'ul'; @endphp
                <{{ $tag }} @class([
                    $tag === 'ol' ? 'list-decimal' : 'list-disc',
                    'pl-6 mb-2 space-y-1',
                    $alignCls,
                ])>
                    @foreach ($block['data']['items'] ?? [] as $li)
                        <li>{!! is_string($li) ? $li : ($li['content'] ?? $li['text'] ?? '') !!}</li>
                    @endforeach
                </{{ $tag }}>
            @else
                @php [$tag, $cls] = $tagMap[$block['type'] ?? 'p'] ?? ['p', '']; @endphp
                <{{ $tag }} @class([$cls, $alignCls])>{!! $block['content'] ?? '' !!}</{{ $tag }}>
            @endif
        @endforeach
    </div>
@endif
