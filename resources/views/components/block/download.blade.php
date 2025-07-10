@props([
    'itemfield' => '',
])



    @if (!empty($itemfield->data))
        @php
            $file = Secondnetwork\Kompass\Models\File::find($itemfield->data);
        @endphp

        @if ($file)
            @if (Storage::disk('local')->exists('/public/' . $file->path . '/' . $file->slug . '.' . $file->extension))
            <div class="relative" wire:sortable.item="{{ $itemfield->id }}">
                <span class="absolute top-2 left-2 " wire:sortable.handle>
                    <x-tabler-arrow-autofit-width class="cursor-move stroke-current h-6 w-6 mr-1 text-gray-800 bg-gray-200 rounded" />
                </span>
                <img on="pages.pages-show" alt="logo" class="w-full aspect-[4/3] object-cover rounded"
                    src="{{ asset('storage/' . $file->path . '/' . $file->slug . '.' . $file->extension) }}">
                <action-button class="absolute flex justify-between items-center w-full bottom-0 right-0 z-10 p-3 gap-1 bg-gray-100/80 ">
                    <div class="text-xs font-semibold truncate">{{ $file->name }}</div>
                    <div class="flex">
                        <span  wire:click="removemedia({{ $itemfield->id }})">
                            <x-tabler-trash class="cursor-pointer stroke-current text-red-500 " />
                        </span>
                        <span wire:click="selectitem('addMedia','{{ $itemfield->type }}',{{ $itemfield->block_id }})">
                            <x-tabler-edit class=" cursor-pointer stroke-current text-blue-500 " />
                        </span>
                    </div>
                </action-button> 

            </div>
            @endif

        @endif
    @endif
