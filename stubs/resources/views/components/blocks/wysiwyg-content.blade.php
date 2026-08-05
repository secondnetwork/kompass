@props([
    'blocks' => [],
])

@php
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
    $alignMap = ['left' => 'text-left', 'center' => 'text-center', 'right' => 'text-right'];
@endphp

@foreach ($blocks as $block)
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
