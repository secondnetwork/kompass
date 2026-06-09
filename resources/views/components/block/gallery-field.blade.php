@props([
    'itemfield' => '',
])

@php
    $images = is_array($itemfield->data) ? $itemfield->data : [];
@endphp

<div class="@container">
    <div class="grid @sm:grid-cols-2 @lg:grid-cols-3 @3xl:grid-cols-4 gap-6">

        @foreach ($images as $fileId)
            @php $file = Secondnetwork\Kompass\Models\File::find($fileId); @endphp
            @if ($file)
                <div class="relative" wire:key="gallery-field-{{ $itemfield->id }}-{{ $fileId }}">
                    <img alt="{{ $file->alt }}" class="w-full aspect-[4/3] object-cover rounded"
                        src="{{ asset('storage/' . $file->path . '/' . $file->slug . '.' . $file->extension) }}">
                    <action-button class="absolute flex justify-between items-center w-full bottom-0 right-0 z-10 p-3 gap-1 bg-gray-100/80">
                        <div class="text-xs font-semibold truncate">{{ $file->name }}</div>
                        <span wire:click="removeFromGalleryField({{ $itemfield->id }}, {{ $fileId }})">
                            <x-tabler-trash class="cursor-pointer stroke-current text-red-500" />
                        </span>
                    </action-button>
                </div>
            @endif
        @endforeach

        <img-block wire:click="selectitem('addMedia', {{ $itemfield->id }}, 'gallery-field', {{ $itemfield->block_id }})"
                   class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 rounded-2xl w-full text-gray-400 aspect-[4/3]">
            <x-tabler-photo-plus class="h-[4rem] w-[4rem] stroke-[1.5]" />
        </img-block>

    </div>
</div>
