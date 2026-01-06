<div class="{{ $class }}">
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
            <x-kompass::video.oembed :url="$datafield->data" :idField="$datafield->id" />
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
