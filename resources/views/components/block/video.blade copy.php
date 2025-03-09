@props(['itemblocks'])
@php
    $cardimg = false;
    $cardoembed = false;
    $box = true;
    $xShow = true;
@endphp

@foreach ($itemblocks->datafield as $itemfields)
    @if ($itemfields->type == 'poster' || $itemfields->type == 'video')
        @php
            $cardimg = true;
            $xShow = false;
        @endphp
    @endif
    @if ($itemfields->type == 'oembed')
        @php
            $cardoembed = true;
            $xShow = false;
        @endphp
    @endif
@endforeach

<div x-data="{ oEmbed:{{ json_encode($cardoembed) }}, videoInt:{{ json_encode($cardimg) }}, box:{{ json_encode($box) }}}">
    @if ($xShow)
        <div class="flex justify-end" x-show="!box">
            <span @click="box = true; oEmbed = false; videoInt = false" class="cursor-pointer p-2 bg-gray-100 rounded-full hover:bg-gray-300 transition-all">
                <x-tabler-x />
            </span>
        </div>
        <div class="grid grid-cols-2 gap-4" x-show="box">
            <button class="btn justify-center" x-show="!oEmbed" x-on:click="oEmbed = true; box = false">
                <x-tabler-brand-youtube/>
                {{ __('embed') }}
            </button>
            <button class="btn justify-center" x-on:click="videoInt = true; box = false">
                <x-tabler-photo-video/>
                {{ __('Add file') }}
            </button>
        </div>
    @endif
    <div x-show="videoInt">
        <div class="@container">
            <div class="grid @sm:grid-cols-1 @lg:grid-cols-3  gap-6">
                @php
                   $cardimg = false;
                @endphp
                @foreach ($itemblocks->datafield as $itemfields)
                    @if ($itemblocks->id == $itemfields->block_id && $itemfields->type == 'poster')
                        @php
                            $cardimg = true;
                        @endphp
                        <x-kompass::blocks :key="$loop->index" type="{{ $itemfields->type }}"
                            name="{{ $itemfields->name }}" fields="{!! $itemfields->data !!}"
                            idField="{{ $itemfields->id }}" blockId="{{ $itemblocks->id }}">
                        </x-kompass::blocks>
                    @endif
                @endforeach
                @if (! $cardimg)
                    <div>
                        <img-block wire:click="selectitem('addMedia',0,'poster',{{ $itemblocks->id }})"
                            class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 rounded-2xl w-full text-gray-400 aspect-video ">
                            <x-tabler-photo-plus class="h-[4rem] w-[4rem] stroke-[1.5]" />
                        </img-block>
                    </div>
                @endif

                @php
                   $cardvideo = false;
                @endphp
                @foreach ($itemblocks->datafield as $itemfields)
                    @if ($itemblocks->id == $itemfields->block_id && $itemfields->type == 'video')
                        @php
                            $cardvideo = true;
                        @endphp
                        <x-kompass::blocks :key="$loop->index" type="{{ $itemfields->type }}"
                            name="{{ $itemfields->name }}" fields="{!! $itemfields->data !!}"
                            idField="{{ $itemfields->id }}" blockId="{{ $itemblocks->id }}">
                        </x-kompass::blocks>
                    @endif
                @endforeach

                @if (! $cardvideo)
                    <div>
                        <img-block wire:click="selectitem('addMedia',0,'video',{{ $itemblocks->id }})"
                            class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 rounded-2xl w-full text-gray-400 aspect-video ">
                            <x-tabler-video-plus class="h-[4rem] w-[4rem] stroke-[1.5]" />
                        </img-block>
                    </div>
                @endif
                <div>
                </div>
            </div>
        </div>
    </div>
    @php
     $cardoembed = false;
    @endphp
    @foreach ($itemblocks->datafield as $itemfields)
        @if ($itemblocks->id == $itemfields->block_id && $itemfields->type == 'oembed')
            @php
              $cardoembed = true;
            @endphp
            <x-kompass::blocks :key="$loop->index" type="{{ $itemfields->type }}"
                name="{{ $itemfields->name }}" fields="{!! $itemfields->data !!}"
                idField="{{ $itemfields->id }}" blockId="{{ $itemblocks->id }}">
            </x-kompass::blocks>
        @endif
    @endforeach
    @if (! $cardoembed)
        <div x-show="oEmbed">
            <div class="flex">YouTube URL</div>
            <form wire:submit="addoEmbed({{ $itemblocks->id }})">
                <x-kompass::form.input wire:model.blur="oembedUrl" type="text" wire:dirty.class="border-yellow" />
            </form>
        </div>
    @endif
</div>