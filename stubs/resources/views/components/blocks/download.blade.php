@props(['item' => ''])

@php
    // Normalizing item to object access for consistency if passed as array
    $item = (object) $item;
    $fieldData = collect($item->datafield);

    // Find image ID (from 'Bild' field)
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
    
    // Find Text content (wysiwyg)
    $textField = $fieldData->firstWhere('name', 'Text');
    $textData = null;
    if ($textField) {
        $rawData = is_array($textField) ? $textField['data'] : $textField->data;
        $textData = is_array($rawData) ? (object) $rawData : json_decode($rawData);
    }
    
    // Find link (button)
    $linkField = $fieldData->firstWhere('type', 'link');
    $link = null;
    if ($linkField) {
        $rawData = is_array($linkField) ? $linkField['data'] : $linkField->data;
        $link = is_array($rawData) ? (object) $rawData : json_decode($rawData);
    }
@endphp

@if($item->type == 'download')
<div class="p-6 rounded-lg shadow-sm flex flex-col items-center justify-between gap-6 text-center mx-5">
        @if ($imageId)
                <div class="mt-auto">
                    <x-image :id="$imageId" class="w-full h-full rounded" />
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
