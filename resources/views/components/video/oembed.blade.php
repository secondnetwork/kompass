@props([
    'url' => null,
    'idField' => null,
    'class' => 'aspect-video',
])

@php
    $videoEmbed = videoEmbed($url);
    $assetExists = $videoEmbed ? Storage::disk('public')->exists('thumbnails-video/'.$videoEmbed['id'].'.jpg') : false;
    $assetUrl = $assetExists ? Storage::disk('public')->url('thumbnails-video/'.$videoEmbed['id'].'.jpg') : null;
@endphp

@if ($videoEmbed)
    <div {{ $attributes->merge(['class' => 'relative group']) }}>
        @if ($videoEmbed['type'] == 'youtube')
            <lite-youtube wire:ignore class="{{ $class }}" videoid="{{ $videoEmbed['id'] }}" params="rel=0" 
                @if($assetExists) style="background-image: url('{{ $assetUrl }}');" @endif>
            </lite-youtube>
        @elseif ($videoEmbed['type'] == 'vimeo')
            <lite-vimeo wire:ignore class="{{ $class }}" videoid="{{ $videoEmbed['id'] }}">
            </lite-vimeo>
        @endif

        @if ($idField)
            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <button type="button" 
                    wire:click="removemedia({{ $idField }})" 
                    class="btn btn-error btn-sm btn-circle"
                    title="{{ __('Delete') }}">
                    <x-tabler-trash class="size-4" />
                </button>
            </div>
        @endif
    </div>
@endif