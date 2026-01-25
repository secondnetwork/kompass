@props(['itemblocks'])
<div class="@container">
    <div class="grid @sm:grid-cols-1 @lg:grid-cols-3 @3xl:grid-cols-4  gap-6"
         wire:sort="updateOrderImages"
         wire:sort.options="{ animation: 100, ghostClass: 'sort-ghost' , chosenClass: 'sort-chosen' ,dragClass: 'sort-drag', removeCloneOnHide: true }">

        @foreach ($itemblocks->datafield as $itemfields)
            <x-kompass::block.image :itemfield="$itemfields" />
        @endforeach

        <img-block wire:click="selectitem('addMedia',0,'gallery',{{ $itemblocks->id }})"
                   class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 rounded-2xl w-full text-gray-400 aspect-[4/3] ">
            <x-tabler-photo-plus class="h-[4rem] w-[4rem] stroke-[1.5]" />
        </img-block>
    </div>
</div>