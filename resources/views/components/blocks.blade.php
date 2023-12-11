@props([
    'idField' => '',
    'name' => '',
    'label' => '',
    'value' => '',
    'type' => '',
    'key' => '',
    'fields' => '',
    'blockId' => '',
])

@if ($label === '')
    @php
        //remove underscores from name
        $label = str_replace('_', ' ', $name);
        //detect subsequent letters starting with a capital
        $label = preg_split('/(?=[A-Z])/', $label);
        //display capital words with a space
        $label = implode(' ', $label);
        //uppercase first letter and lower the rest of a word
        $label = ucwords(strtolower($label));
    @endphp
@endif

@if ($type == 'text')
    <x-kompass::form.input wire:model="fields.{{ $key }}.data" label="{{ $name }}" type="text" />
@endif

@if ($type == 'wysiwyg')
    <span class="text-md">{{ $name }}</span>

                                @livewire(
                                    'editorjs',
                                    [
                                        'editorId' => $idField,
                                        'value' => json_decode($fields, true),
                                        'uploadDisk' => 'public',
                                        'downloadDisk' => 'public',
                                        'class' => 'cdx-input',
                                        'style' => '',
                                        'readOnly' => false,
                                        'placeholder' => __('write something...'),
                                    ],
                  
                                )

                                

    {{-- <x-quill id="{{ $key }}" wire:model="fields.{{ $key }}.data" /> --}}
@endif

@if ($type == 'image')

    @if (!empty($fields))
        @php
            $file = Secondnetwork\Kompass\Models\File::find($fields);
        @endphp

        @if ($file)
            @if (Storage::disk('local')->exists('/public/' . $file->path . '/' . $file->slug . '.' . $file->extension))
            <div class="relative">
            
                <img on="pages.pages-show" alt="logo" class="w-full aspect-[4/3] object-cover rounded-xl"
                    src="{{ asset('storage/' . $file->path . '/' . $file->slug . '.' . $file->extension) }}">
                <action-button class="absolute flex justify-between items-center w-full bottom-0 right-0 z-10 p-3 gap-1 bg-gray-100/80 ">
                    <div class="text-xs font-semibold truncate">{{ $file->name }}</div>
                    <div class="flex">
                        <span  wire:click="removemedia({{ $idField }})">
                            <x-tabler-trash class="cursor-pointer stroke-current text-red-500 " />
                        </span>
                        <span wire:click="selectitem('addMedia','{{ $type }}',{{ $blockId }})">
                            <x-tabler-edit class=" cursor-pointer stroke-current text-blue-500 " />
                        </span>
                    </div>
                </action-button> 

            </div>
            @endif

        @endif
    @else
        <img-block wire:click="selectitem('addMedia',{{ $idField }},'{{ $type }}',{{ $blockId }})"
            class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 rounded-2xl text-gray-400 aspect-[4/3] ">
            <x-tabler-photo-plus class="h-[4rem] w-[4rem] stroke-[1.5]" />
        </img-block>
    @endif

@endif

@if ($type == 'poster')
      
    @if (!empty($fields))
        @php
            $file = Secondnetwork\Kompass\Models\File::find($fields);
        @endphp

        @if ($file)
            @if (Storage::disk('local')->exists('/public/' . $file->path . '/' . $file->slug . '.' . $file->extension))
            <div class="relative">

                <img on="pages.pages-show" alt="logo" class="w-full aspect-[16/9] object-cover rounded-xl"
                    src="{{ asset('storage/' . $file->path . '/' . $file->slug . '.' . $file->extension) }}">
                <action-button class="absolute flex justify-between items-center w-full bottom-0 right-0 z-10 p-3 gap-1 bg-gray-100/80 ">
                    <div class="text-xs font-semibold truncate">{{ $file->name }}</div>
                    <div class="flex">
                        <span  wire:click="removemedia({{ $idField }})">
                            <x-tabler-trash class="cursor-pointer stroke-current text-red-500 " />
                        </span>
                        <span wire:click="selectitem('addMedia','{{ $type }}',{{ $blockId }})">
                            <x-tabler-edit class=" cursor-pointer stroke-current text-blue-500 " />
                        </span>
                    </div>
                </action-button> 


       
        </div>
            @endif

        @endif

    @endif

@endif

@if ($type == 'video')

    @if (!empty($fields))
        @php
            $file = Secondnetwork\Kompass\Models\File::find($fields);
        @endphp

        @if ($file)
            @if (Storage::disk('local')->exists('/public/' . $file->path . '/' . $file->slug . '.' . $file->extension))
            <data-item class="bg-white block shadow rounded">

                <div class="relative text-sm font-bold rounded-tr-lg rounded-tl-lg w-full aspect-[16/9] bg-cover bg-center bg-gray-300 overflow-hidden">

                <video controls class="object-cover h-full"
                src="{{ asset($file->path . '/' . $file->slug . '.' . $file->extension) }}"></video>

                <action-button class="absolute flex justify-between items-center w-full bottom-0 right-0 z-10 p-3 gap-1 bg-gray-100/80 ">
                    <div class="text-xs font-semibold truncate">{{ $file->name }}</div>
                    <div class="flex">
                        <span  wire:click="removemedia({{ $idField }})">
                            <x-tabler-trash class="cursor-pointer stroke-current text-red-500 " />
                        </span>
                        <span wire:click="selectitem('addMedia','{{ $type }}',{{ $blockId }})">
                            <x-tabler-edit class=" cursor-pointer stroke-current text-blue-500 " />
                        </span>
                    </div>
                </action-button> 
                </div>
            </data-item>
            @endif

        @endif

    @endif

@endif

@if ($type == 'gallery')

    @if (!empty($fields))
        @php
            $file = Secondnetwork\Kompass\Models\File::find($fields);
        @endphp

        @if ($file)
            @if (Storage::disk('local')->exists('/public/' . $file->path . '/' . $file->slug . '.' . $file->extension))
                <div class="relative">
            
                        <img on="pages.pages-show" alt="logo" class="w-full aspect-[4/3] object-cover rounded-xl"
                            src="{{ asset('storage/' . $file->path . '/' . $file->slug . '.' . $file->extension) }}">
                        <action-button class="absolute flex justify-between items-center w-full bottom-0 right-0 z-10 p-3 gap-1 bg-gray-100/80 ">
                            <div class="text-xs font-semibold truncate">{{ $file->name }}</div>
                            <div class="flex">
                                <span  wire:click="removemedia({{ $idField }})">
                                    <x-tabler-trash class="cursor-pointer stroke-current text-red-500 " />
                                </span>
    
                                <span wire:click="selectitem( 'addMedia', {{ $idField }})">
                                    <x-tabler-edit class=" cursor-pointer stroke-current text-blue-500 " />
                                </span>
                            </div>
                        </action-button> 
                </div>

                @endif
        @endif

    @endif
@endif

@if ($type == 'oembed')

        @php
          $videoEmbed =  videoEmbed($fields); 
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
    <div class="" @click="box = true, oEmbed = false" wire:click="removemedia({{ $idField }})">
        <button wire:click="delete" type="button" class="btn btn-danger bg-red-500"><x-tabler-trash class="cursor-pointer stroke-current" />  {{ __('Delete') }}</button>
    </div>
@endif

@endif

@if ($type == 'true_false')
    <label wire:click="selected('{{ $idField }}')" for="default-toggle"
        class="inline-flex relative items-center cursor-pointer">
        <input type="checkbox" @if ($fields == 1) checked @endif class="sr-only peer">
        <div
            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
        </div>
        <span class="ml-3 text-md font-medium">{{ $name }}</span>
    </label>
@endif

