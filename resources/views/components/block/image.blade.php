@props([
    'itemfield' => '',
])


<div class="@container">
  <div class="grid @sm:grid-cols-1 @lg:grid-cols-3 @3xl:grid-cols-4  gap-6">

    @if (!empty($itemfield->data))
        @php
            $file = Secondnetwork\Kompass\Models\File::find($itemfield->data);
        @endphp

        @if ($file)
            @if (Storage::disk('local')->exists('/public/' . $file->path . '/' . $file->slug . '.' . $file->extension))
            <div class="relative">
            
                <img on="pages.pages-show" alt="logo" class="w-full aspect-[4/3] object-cover rounded-xl"
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

  </div>
</div>