@props(['itemblocks'])

@php
    $datafields = $itemblocks->datafield;
    $oembedField = $datafields->firstWhere('type', 'oembed');
    $videoField = $datafields->firstWhere('type', 'video');
    $posterField = $datafields->firstWhere('type', 'poster');
    
    $initialActiveTab = $oembedField ? 'oembed' : ($videoField ? 'upload' : 'oembed');
@endphp

<div>

    <div class="space-y-4 py-6">
        @if ($oembedField && $oembedField->data)
            <x-kompass::video.oembed :url="$oembedField->data" :idField="$oembedField->id" />
        @else
            <form wire:submit="addoEmbed({{ $itemblocks->id }})">
                <x-kompass::input wire:model.blur="oembedUrl" type="text" label="{{ __('Video URL (YouTube/Vimeo):') }}" placeholder="https://www.youtube.com/watch?v=..." />
                <p class="text-xs text-gray-500 mt-1">{{ __('Press Enter to confirm') }}</p>
            </form>
        @endif
    </div>

    <div class="@container">
        <div class="grid grid-cols-3 gap-6">


            {{-- Poster Upload --}}
            @if ($posterField && $posterField->data)
                @php $posterFile = Secondnetwork\Kompass\Models\File::find($posterField->data); @endphp
                @if($posterFile)
                <div class="relative group">
                    <img src="{{ asset('storage/' . $posterFile->path . '/' . $posterFile->slug . '.' . $posterFile->extension) }}" 
                        class="w-full aspect-video object-cover rounded-xl shadow-sm">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-xl">
                        <div class="flex gap-2">
                            <button wire:click="removemedia({{ $posterField->id }})" class="btn btn-error btn-xs">{{ __('Remove Poster') }}</button>
                        </div>
                    </div>
                </div>
                @endif
            @else
                <div wire:click="selectitem('addMedia', 0, 'poster', {{ $itemblocks->id }})"
                    class="cursor-pointer flex flex-col items-center justify-center border-2 border-dashed border-gray-400 rounded-2xl aspect-video text-gray-400 hover:border-primary hover:text-primary transition-colors">
                    <x-tabler-photo-plus class="size-12 stroke-[1.5]" />
                    <span class="mt-2 text-sm font-medium">{{ __('Add Poster Image') }}</span>
                </div>
            @endif

                        {{-- Video Upload --}}
            @if ($videoField && $videoField->data)
                <x-kompass::video.local 
                    :video="$videoField->data" 
                    :poster="$posterField?->data" 
                    :idField="$videoField->id" 
                    :blockId="$itemblocks->id"
                    editable />
            @else
                <div wire:click="selectitem('addMedia', 0, 'video', {{ $itemblocks->id }})"
                    class="cursor-pointer flex flex-col items-center justify-center border-2 border-dashed border-gray-400 rounded-2xl aspect-video text-gray-400 hover:border-primary hover:text-primary transition-colors">
                    <x-tabler-video-plus class="size-12 stroke-[1.5]" />
                    <span class="mt-2 text-sm font-medium">{{ __('Add Video') }}</span>
                </div>
            @endif
        </div>
    </div>
</div>
