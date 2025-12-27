@props([
    'video' => null,
    'poster' => null,
    'idField' => null,
    'blockId' => null,
    'editable' => false,
])

@php
    $videoFile = $video ? Secondnetwork\Kompass\Models\File::find($video) : null;
    $posterFile = $poster ? Secondnetwork\Kompass\Models\File::find($poster) : null;
@endphp

<div {{ $attributes->merge(['class' => 'relative group rounded-xl overflow-hidden bg-gray-100 aspect-video']) }}>
    @if ($videoFile)
        <video 
            controls 
            class="w-full h-full object-cover"
            @if($posterFile) poster="{{ asset('storage/' . $posterFile->path . '/' . $posterFile->slug . '.' . $posterFile->extension) }}" @endif
            src="{{ asset('storage/' . $videoFile->path . '/' . $videoFile->slug . '.' . $videoFile->extension) }}">
        </video>
    @elseif ($posterFile)
        <img 
            src="{{ asset('storage/' . $posterFile->path . '/' . $posterFile->slug . '.' . $posterFile->extension) }}" 
            class="w-full h-full object-cover"
            alt="Video Poster">
    @else
        <div class="flex items-center justify-center h-full text-gray-400">
            <x-tabler-video-off class="size-12 stroke-[1]" />
        </div>
    @endif

    @if ($editable && $idField)
        <div class="absolute bottom-0 right-0 left-0 p-3 bg-gray-900/60 flex justify-between items-center opacity-0 group-hover:opacity-100 transition-opacity">
            <span class="text-white text-xs truncate">{{ $videoFile?->name ?? $posterFile?->name ?? '' }}</span>
            <div class="flex gap-2">
                <button wire:click="removemedia({{ $idField }})" class="btn btn-error btn-xs btn-circle">
                    <x-tabler-trash class="size-3" />
                </button>
                <button wire:click="selectitem('addMedia', 'video', {{ $blockId }})" class="btn btn-primary btn-xs btn-circle">
                    <x-tabler-edit class="size-3" />
                </button>
            </div>
        </div>
    @endif
</div>