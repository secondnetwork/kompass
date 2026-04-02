@props(['item' => ''])

@if ($item->type == 'card')
    @php
        $cssclassname = get_meta($item, 'css-classname', 'bg-white');
        $image = get_field('image', $item->datafield);
        $title = get_field('wysiwyg', $item->datafield);
        $titleData = $title ? (is_string($title) ? json_decode($title) : (object) $title) : null;
        $text = get_field('text', $item->datafield);
        $text = $text ? (is_string($text) ? json_decode($text) : $text) : null;
        $link = get_field('link', $item->datafield);
        $link = $link ? (is_string($link) ? json_decode($link) : (object) $link) : null;
    @endphp

    <div class="card {{ $cssclassname }} rounded">
        <div class="card-body flex flex-col p-0 rounded">
            <div class="p-10 pb-0">
                @if ($titleData && isset($titleData->blocks))
                    @foreach ($titleData->blocks as $block)
                        @php
                            $block = (object) $block;
                            $blockData = (object) $block->data;
                        @endphp
                        @switch($block->type)
                            @case('header')
                                <h{{ $blockData->level }} class="text-4xl md:text-6xl font-bold mb-4">
                                    {!! $blockData->text !!}
                                </h{{ $blockData->level }}>
                                @break
                            @case('paragraph')
                                <p class="text-xl mb-4">{!! $blockData->text !!}</p>
                                @break
                        @endswitch
                    @endforeach
                @endif
            </div>

            @if ($text)
                <p>{!! $text !!}</p>
            @endif

            @if ($link)
                <div class="card-actions justify-end">
                    <a href="{{ $link->url ?? '#' }}" class="btn btn-primary">
                        {{ $link->title ?? ' Mehr erfahren' }}
                    </a>
                </div>
            @endif
            @if ($image)
                <div class="mt-auto">
                    <x-image :id="$image" class="w-full h-full rounded" />
                </div>
            @endif
        </div>
    </div>
@endif
