<div>

    @switch($datafield->type)
        @case('image')
            <div class="@container">
                <div class="grid @sm:grid-cols-1 @lg:grid-cols-3  gap-6">

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

        @case('true_false')
  
        <label wire:click="selected('{{ $datafield->id}}')" for="default-toggle"
            class="inline-flex relative items-center cursor-pointer">
            <input type="checkbox" @if ($datafield->data == 1) checked @endif class="sr-only peer">
            <div
                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
            </div>
            <span class="ml-3 text-md font-medium">{{ $name }}</span>
        </label>
    
        @break

        @case('wysiwyg')

                                @livewire(
                                    'editorjs',
                                    [
                                        'editorId' => $datafield->id,
                                        'value' => json_decode($datafield->data, true),
                                        'uploadDisk' => 'public',
                                        'downloadDisk' => 'public',
                                        'class' => 'cdx-input',
                                        'style' => '',
                                        'readOnly' => false,
                                        'placeholder' => __('write something...'),
                                    ],
                  
                                )
@break
        @default
            <x-kompass::form.input wire:model="data" label="{{ $datafield->name }}" type="text" />
    @endswitch
</div>
