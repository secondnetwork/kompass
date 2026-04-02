@props([
    'item' => '',
])

@if($item->type == 'button')
@php
    $url  = get_field('text_url', $item->datafield);
    $text = get_field('text', $item->datafield);
    $icon = get_field('icon', $item->datafield);
@endphp
<div>
    <a class="btn inline-flex fill-current" href="{{ $url }}">{{ $text }}
        @if (!empty($icon))
            @svg($icon)
        @endif
    </a>
</div>
@endif
