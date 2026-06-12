@props(['itemblocks'])

@php
    $galleryField = $itemblocks->datafield->firstWhere('type', 'gallery');

    if (is_array($galleryField?->data)) {
        // New model: single row, data = [id, id, ...]
        $images   = $galleryField->data;
        $fieldId  = $galleryField->id;
        $isLegacy = false;
    } else {
        // Legacy model: multiple rows, each with a single integer in data
        $images = $itemblocks->datafield
            ->where('type', 'gallery')
            ->filter(fn ($d) => !empty($d->data) && !is_array($d->data))
            ->pluck('data')
            ->toArray();
        $fieldId  = 0;
        $isLegacy = true;
    }
@endphp

<div class="@container">
    <div class="grid @sm:grid-cols-1 @lg:grid-cols-3 @3xl:grid-cols-4 gap-6"
         @unless ($isLegacy)
             wire:sort="updateGalleryOrder"
             wire:sort:config="{ animation: 100, ghostClass: 'sort-ghost', chosenClass: 'sort-chosen', dragClass: 'sort-drag' }"
         @endunless>

        @foreach ($images as $fileId)
            @php $file = Secondnetwork\Kompass\Models\File::find($fileId); @endphp
            @if ($file)
                <div class="relative"
                     wire:key="gallery-{{ $itemblocks->id }}-{{ $fileId }}"
                     @unless ($isLegacy) wire:sort:item="{{ $fieldId }}-{{ $fileId }}" @endunless>

                    @unless ($isLegacy)
                        <span wire:sort:handle class="absolute top-2 left-2 z-10">
                            <x-tabler-arrow-autofit-width class="cursor-move stroke-current size-5 text-gray-800 bg-gray-200/80 rounded" />
                        </span>
                    @endunless

                    <img alt="{{ $file->alt }}" class="w-full aspect-[4/3] object-cover rounded"
                        src="{{ asset('storage/' . $file->path . '/' . $file->slug . '.' . $file->extension) }}">

                    @unless ($isLegacy)
                        <action-button class="absolute flex justify-between items-center w-full bottom-0 right-0 z-10 p-3 gap-1 bg-gray-100/80">
                            <div class="text-xs font-semibold truncate">{{ $file->name }}</div>
                            <span wire:click="removeFromGalleryField({{ $fieldId }}, {{ $fileId }})">
                                <x-tabler-trash class="cursor-pointer stroke-current text-red-500" />
                            </span>
                        </action-button>
                    @endunless
                </div>
            @endif
        @endforeach

        <img-block wire:click="selectitem('addMedia', {{ $fieldId }}, 'gallery-field', {{ $itemblocks->id }})"
                   class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 rounded-2xl w-full text-gray-400 aspect-[4/3]">
            <x-tabler-photo-plus class="h-[4rem] w-[4rem] stroke-[1.5]" />
        </img-block>

    </div>
</div>
