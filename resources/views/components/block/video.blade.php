@props(['itemblocks'])


@php
    $hasPoster = false;
    $hasVideo = false;
    $hasOembed = false;
    foreach ($itemblocks->datafield as $itemfield) {
        if ($itemfield->type === 'poster') {
            $hasPoster = true;
        } elseif ($itemfield->type === 'video') {
            $hasVideo = true;
        } elseif ($itemfield->type === 'oembed') {
            $hasOembed = true;
        }
    }
    $boxValue = !$hasOembed && !$hasVideo;
    // Determine initial active tab based on $hasOembed and $hasVideo
    $initialActiveTab = $hasOembed ? 'oembed' : ($hasVideo ? 'upload' : 'oembed');
@endphp


<div>
    <div x-data="{ activeTab: '{{ $initialActiveTab }}' }">
        @if ($boxValue)
            
        @endif
        <div class="mb-4">
            <button @click="activeTab = 'oembed'" :class="{ 'bg-blue-600 text-white': activeTab === 'oembed', 'bg-gray-200 text-gray-700': activeTab !== 'oembed' }" class="px-4 py-2 rounded">oEmbed (YouTube)</button>
            <button @click="activeTab = 'upload'" :class="{ 'bg-blue-600 text-white': activeTab === 'upload', 'bg-gray-200 text-gray-700': activeTab !== 'upload' }" class="px-4 py-2 rounded ml-2">Video Upload</button>
        </div>

        <div x-show="activeTab === 'oembed'">

            @forelse ($itemblocks->datafield as  $itemfields  )
                <livewire:datafield-item :datafield="$itemfields" :key="$itemfields->id" />
            @empty 
                <form wire:submit="addoEmbed({{ $itemblocks->id }})">
                    <x-kompass::input wire:model.blur="oembedUrl" type="text" label="YouTube/Vimeo URL:"  wire:dirty.class="border-yellow" />
                </form>
            @endforelse

        </div>

        <div x-show="activeTab === 'upload'">
    
            <div class="@container">
                <div class="grid @sm:grid-cols-1 @lg:grid-cols-3  gap-6">

                     @if ($hasVideo)
                         @foreach ($itemblocks->datafield as $itemfield)
                            @if ($itemfield->type === 'video')
                                 <x-kompass::blocks :key="$loop->index" type="{{ $itemfield->type }}"
                                      name="{{ $itemfield->name }}" fields="{!! $itemfield->data !!}"
                                     idField="{{ $itemfield->id }}" blockId="{{ $itemblocks->id }}">
                                </x-kompass::blocks>
                            @endif
                         @endforeach
                    @else
                           <div>
                              <img-block wire:click="selectitem('addMedia',0,'video',{{ $itemblocks->id }})"
                                  class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 rounded-2xl w-full text-gray-400 aspect-video ">
                                   <x-tabler-video-plus class="h-[4rem] w-[4rem] stroke-[1.5]" />
                               </img-block>
                            </div>
                     @endif

                     @if ($hasPoster)
                        @foreach ($itemblocks->datafield as $itemfield)
                            @if ($itemfield->type === 'poster')
                                <x-kompass::blocks :key="$loop->index" type="{{ $itemfield->type }}"
                                    name="{{ $itemfield->name }}" fields="{!! $itemfield->data !!}"
                                    idField="{{ $itemfield->id }}" blockId="{{ $itemblocks->id }}">
                                </x-kompass::blocks>
                            @endif
                        @endforeach
                    @else
                        <div>
                            <img-block wire:click="selectitem('addMedia',0,'poster',{{ $itemblocks->id }})"
                                    class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 rounded-2xl w-full text-gray-400 aspect-video ">
                                <x-tabler-photo-plus class="h-[4rem] w-[4rem] stroke-[1.5]" />
                            </img-block>
                        </div>
                    @endif

                </div>
            </div>

           
         </div>
    </div>
</div>