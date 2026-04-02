@props(['item' => ''])

@if($item->type == 'download')
@php
    $image = get_field('image', $item->datafield);
    $text = get_field('wysiwyg', $item->datafield);
    $textData = $text ? (is_string($text) ? json_decode($text) : (is_array($text) ? (object) $text : $text)) : null;
    $link = get_field('link', $item->datafield);
    $link = $link ? (is_string($link) ? json_decode($link) : (is_array($link) ? (object) $link : $link)) : null;
@endphp

<div class="p-6 rounded-lg shadow-sm flex flex-col items-center justify-between gap-6 text-center mx-5">
    @if ($image)
        <div class="mt-auto">
            <x-image :id="$image" class="w-full h-full rounded" />
        </div>
    @endif

    <div class="">
        @if($textData && isset($textData->blocks))
            <div class="prose max-w-none">
                @foreach ($textData->blocks as $block)
                    @php $block = (object) $block; $blockData = (object) $block->data; @endphp
                    @if($block->type === 'paragraph')
                        <p class="text-gray-700 m-0">{!! $blockData->text !!}</p>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    @if($link)
        <div class="flex-shrink-0">
            <a href="{{ $link->url ?? '#' }}" class="btn btn-primary flex items-center gap-2">
                {{ $link->title ?? 'Download' }}
                <x-tabler-download class="w-5 h-5" />
            </a>
        </div>
    @endif
</div>
@endif
