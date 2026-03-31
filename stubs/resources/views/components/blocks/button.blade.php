@props([
    'item' => '',
])
@if($item->type == 'button')
<div>
@php

    foreach ($item->datafield as $item) {
        $button[] = array(
            'type'  => $item->type,
            'data' => $item->data,
        );
    }
    $btn = collect($button)->mapWithKeys(function ($item) {
    return [$item['type'] => $item['data']];
    });;

@endphp
    <a class="btn inline-flex fill-current" href="{{ $btn->get('text_url') }}">{{ $btn->get('text')  }}
        @if (!empty($btn->get('icon')))
            @svg($btn->get('icon'))
        @endif
    </a>
</div>
@endif
