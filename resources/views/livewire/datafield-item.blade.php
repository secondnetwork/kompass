<div>

    @switch($datafield->type)
        @case('image')
            <div class="@container">
                <div class="grid @sm:grid-cols-1 @lg:grid-cols-3 @3xl:grid-cols-4  gap-6" wire:sortable="updateOrderImages"
                    wire:sortable.options="{ animation: 100, ghostClass: 'sort-ghost' , chosenClass: 'sort-chosen' ,dragClass: 'sort-drag', removeCloneOnHide: true }">

                    <x-kompass::block.image :itemfield="$datafield" />

                    @if ($datafield->data == null)
                        <img-block wire:click="selectitem('addMedia',{{ $datafield->id }},'image',{{ $datafield->block_id }})"
                            class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 rounded-2xl w-full text-gray-400 aspect-[4/3] ">
                            <x-tabler-photo-plus class="h-[4rem] w-[4rem] stroke-[1.5]" />
                        </img-block>
                    @endif

                </div>
            </div>
        @break
        @case('oembed')
            @php
            $videoEmbed =  videoEmbed($datafield->data); 
            $assetExists = Storage::disk('public')->exists('thumbnails-video/'.$videoEmbed['id'].'.jpg');
            $assetUrl = Storage::disk('public')->url('thumbnails-video/'.$videoEmbed['id'].'.jpg');
            @endphp

            @if ($videoEmbed)
                @if ($videoEmbed['type'] == 'youtube')
                <lite-youtube wire:ignore class="aspect-video" videoid="{{ $videoEmbed['id'] }}" params="rel=0" @if($assetExists) style="background-image: url('{{ $assetUrl }}');" @endif></lite-youtube>
                @endif
                @if ($videoEmbed['type'] == 'vimeo')
                <lite-vimeo wire:ignore class="aspect-video" videoid="{{ $videoEmbed['id'] }}"></lite-vimeo>
                @endif
                <div class="" @click="box = true, oEmbed = false" wire:click="removemedia({{ $datafield->id }})">
                    <button wire:click="delete" type="button" class="btn btn-danger bg-red-500"><x-tabler-trash class="cursor-pointer stroke-current" />  {{ __('Delete') }}</button>
                </div>
            @endif
        @break

        @case('gallery')
     
            <div class="@container">
                <div class="grid @sm:grid-cols-1 @lg:grid-cols-3 @3xl:grid-cols-4  gap-6" wire:sortable="updateOrderImages"
                    wire:sortable.options="{ animation: 100, ghostClass: 'sort-ghost' , chosenClass: 'sort-chosen' ,dragClass: 'sort-drag', removeCloneOnHide: true }">


                    <x-kompass::block.image :itemfield="$datafield" />

                    @if ($datafield->data == null)
                        <img-block wire:click="selectitem('addMedia',{{ $datafield->id }},'image',{{ $datafield->block_id }})"
                            class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 rounded-2xl w-full text-gray-400 aspect-[4/3] ">
                            <x-tabler-photo-plus class="h-[4rem] w-[4rem] stroke-[1.5]" />
                        </img-block>
                    @endif

                </div>
            </div>
        @break

        @case('wysiwyg')
            @livewire('editorjs', [
                'editorId' => $datafield->id,
                'value' => json_decode($datafield->data, true),
                'uploadDisk' => 'public',
                'downloadDisk' => 'public',
                'class' => 'cdx-input',
                'style' => '',
                'readOnly' => false,
                'placeholder' => __('write something...'),
            ])
        @break

        @default
            <x-kompass::input wire:model="data" label="{{ $datafield->name }}" type="text" />
    @endswitch
</div>
