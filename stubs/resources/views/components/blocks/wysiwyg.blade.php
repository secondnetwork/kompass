@props([
    'item' => '',
])

@if ('wysiwyg' == $item->type)
@php
    $alignment = $item->getMeta('alignment');
    $alignmentClass = match($alignment) {
        'align-left' => 'text-left flex flex-col justify-center',
        'align-center' => 'text-center flex flex-col justify-center',
        'align-right' => 'text-right flex flex-col justify-center',
        default => 'flex flex-col justify-center',
    };
    $layoutgrid = $item->layoutgrid ?? 12;
    $gridCols = 'grid-cols-' . $layoutgrid;
    $colSpan = $item->layoutgrid ? 'col-span-' . $item->layoutgrid : '';
@endphp
    <div {{ $attributes->merge(['class' => $alignmentClass . ' ' . $gridCols . ' ' . $colSpan]) }}>
        @php
            $fieldData = get_field('wysiwyg',$item->datafield);
            $content = is_array($fieldData) || is_object($fieldData) ? $fieldData : ($fieldData ?? '');
        @endphp

        @if($content)
            <div class="tiptap-output prose prose-sm dark:prose-invert max-w-none">
                {!! $content !!}
            </div>
        @endif
    </div>
@endif
