@props([
    'item' => '',
    'field' => null,
])

@php
    if ($field) {
        $btn = is_array($field->data) ? $field->data : (array) $field->data;
    } else {
        $btn = [];
        foreach ($item->datafield as $f) {
            if ($f->type === 'link' && is_array($f->data)) {
                $btn = array_merge($btn, $f->data);
            } else {
                $btn[$f->type] = $f->data;
            }
        }
    }
    $iconPosition = $btn['iconposition'] ?? 'left';
    $layoutgrid = is_object($item) ? ($item->layoutgrid ?? 12) : 12;
    $gridCols = 'md:grid-cols-' . $layoutgrid;
    $colSpan = is_object($item) && $item->layoutgrid ? 'md:col-span-' . $item->layoutgrid : '';
@endphp

@if ($field || $item->type == 'button')
<span {{ $attributes->merge(['class' => 'w-full block mx-auto ' . $gridCols . ' ' . $colSpan]) }}>
    <a class="btn btn-primary btn-lg fill-current mx-auto flex" href="{{ $btn['url'] ?? '#' }}">
        @if (!empty($btn['iconclass']))
            @if($iconPosition === 'left')
                @svg($btn['iconclass'], 'w-8 h-8 mr-2')
            @endif
        @endif
        {{ $btn['title'] ?? '' }}
        @if (!empty($btn['iconclass']))
            @if($iconPosition === 'right')
                @svg($btn['iconclass'], 'w-8 h-8 ml-2')
            @endif
        @endif
    </a>
</span>

@endif
