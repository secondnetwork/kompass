@props([
    'item' => '',
    'field' => null,
])

@php
    if ($field) {
        $rawData = $field->data;
    } else {
        $rawData = is_object($item) ? get_field('wysiwyg', $item->datafield) : null;
    }

    $data = is_array($rawData) ? json_decode(json_encode($rawData)) : (is_string($rawData) ? json_decode($rawData) : $rawData);
@endphp

@if ($field || (is_object($item) && 'wysiwyg' == $item->type))
@php
    $linkUrl = get_meta($item, 'link-url');
    $alignment = get_meta($item, 'alignment');
    $cssclassname = get_meta($item, 'css-classname', '');
    $alignmentClass = match($alignment) {
        'align-left' => 'text-left ',
        'align-center' => 'text-center ',
        'align-right' => 'text-right ',
        default => '',
    };
    $layoutgrid = is_object($item) ? ($item->layoutgrid ?? 12) : 12;
    $gridCols = 'md:grid-cols-' . $layoutgrid;
    $colSpan = is_object($item) && $item->layoutgrid ? 'md:col-span-' . $item->layoutgrid : '';
@endphp
    <div {{ $attributes->merge(['class' => 'relative group ' . $cssclassname . ' ' . $alignmentClass . ' ' . $gridCols . ' ' . $colSpan]) }}>
        @if($linkUrl)
            <a href="{{ $linkUrl }}" class="block absolute inset-0 z-10"></a>
            <div class="group-hover:bg-violet-900/60 transition block absolute inset-0 rounded-2xl -z-10"></div>
        @endif
        @if($data)
            @foreach ($data->blocks as $block)
                @switch($block->type)
                    @case('header')
                        <h{{ $block->data->level }}>
                            {!! $block->data->text !!}
                        </h{{ $block->data->level }}>
                        @break
                    @case('paragraph')
                        <p>{!! $block->data->text !!}</p>
                        @break
                    @case('list')
                        <ul class="list-disc pl-4">
                            @foreach ($block->data->items as $items)
                                <li>{!! is_object($items) ? ($items->text ?? '') : $items !!}</li>
                            @endforeach
                        </ul>
                        @break
                    @case('table')
                        <table>
                            @foreach ($block->data->content as $items)
                                <tr class="@if ($loop->first && $loop->index == 0) bg-slate-200 font-bold @endif">
                                    @foreach ($items as $item)
                                        <td class="p-4">{!! is_object($item) ? ($item->text ?? '') : $item !!}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </table>
                        @break
                @endswitch
            @endforeach
        @endif
    </div>
    @if($linkUrl)
    </a>
    @endif
@endif
