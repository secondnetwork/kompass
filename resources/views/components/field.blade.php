@props([
    'item' => null,
    'name' => '',
    'class' => null,
    'size' => null,
    'default' => null,
    'as' => 'auto',
])

@php
    $data = $item->datafield ?? [];
    $value = $default;

    if ($as === 'int' || $as === 'integer') {
        $value = get_field_as($name, $data, 'int', $default);
    } elseif ($as === 'bool' || $as === 'boolean') {
        $value = get_field_as($name, $data, 'bool', $default);
    } elseif ($as === 'string') {
        $value = get_field_as($name, $data, 'string', $default);
    } elseif ($as === 'array') {
        $value = get_field_as($name, $data, 'array', $default);
    } else {
        $value = get_field($name, $data, $class, $size, $default);
    }
@endphp

@if ($as === 'image' || $as === 'gallery')
    @php
        $value = get_field($name, $data, $class, $size, $default);
    @endphp
@endif

@if (!is_null($value) && $value !== $default)
    @if ($as === 'image' || $as === 'gallery')
        {!! $value !!}
    @elseif ($name === 'wysiwyg' || $name === 'text')
        {!! $value !!}
    @elseif ($name === 'link' || $name === 'button')
        @php
            $linkData = is_string($value) ? json_decode($value) : (object) $value;
        @endphp
        @if ($linkData)
            <a href="{{ $linkData->url ?? '#' }}" {{ $attributes->merge(['class' => $class ?? '']) }}>
                {{ $linkData->title ?? '' }}
            </a>
        @endif
    @else
        {{ $value }}
    @endif
@elseif (!is_null($default) && $value === $default)
    {{ $default }}
@endif
