@props(['item' => ''])

@if ($item->type == 'card')
    @php
        $fieldData = collect($item->datafield);
        $cssclassname = $item->getMeta('css-classname') ?? 'bg-white';
        // Find image field
        $imageField = $fieldData->firstWhere('type', 'image');
        $imageId = null;
        if ($imageField) {
            $imageData = is_array($imageField) ? $imageField['data'] ?? null : $imageField->data ?? null;
            // Image data might be just an ID or an object with id
            if (is_numeric($imageData)) {
                $imageId = $imageData;
            } elseif (is_object($imageData) && isset($imageData->id)) {
                $imageId = $imageData->id;
            }
        }

        // Find title (wysiwyg)
        $titleField = $fieldData->firstWhere('type', 'wysiwyg');
        $titleData = null;
        if ($titleField) {
            $rawTitle = is_array($titleField) ? $titleField['data'] ?? null : $titleField->data ?? null;
            $titleData = is_string($rawTitle) ? json_decode($rawTitle) : (object) $rawTitle;
        }

        // Find text content
        $textField = $fieldData->firstWhere('type', 'text');
        $text = null;
        if ($textField) {
            $rawText = is_array($textField) ? $textField['data'] ?? null : $textField->data ?? null;
            $text = is_string($rawText) ? json_decode($rawText) : $rawText;
        }

        // Find link button
        $linkField = $fieldData->firstWhere('type', 'link');
        $link = null;
        if ($linkField) {
            $rawLink = is_array($linkField) ? $linkField['data'] ?? null : $linkField->data ?? null;
            $link = is_string($rawLink) ? json_decode($rawLink) : (object) $rawLink;
        }
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
            @if ($imageId)
                <div class="mt-auto">
                    <x-image :id="$imageId" class="w-full h-full rounded" />
                </div>
            @endif
        </div>

    </div>
@endif
