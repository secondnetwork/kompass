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
            <img-block class="cursor-pointer grid place-content-center border-2 border-gray-600 rounded w-full  aspect-[4/3] ">
            

            <div class="absolute rounded top-2 right-2 text-sm text-gray-600 bg-gray-200 uppercase py-1 px-3 font-semibold">{{ $file->extension }}</div>
            <x-tabler-file class="h-[4rem] w-[4rem] stroke-[1.5]" />
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

            </img-block>
            @endif

        @endif
    @endif

  </div>
</div>