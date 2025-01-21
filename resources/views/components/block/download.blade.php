@props(['itemblocks'])
<div class="@container">
    <div class="grid @sm:grid-cols-1 @lg:grid-cols-3 @3xl:grid-cols-4  gap-6">
        @foreach ($itemblocks->datafield as $itemfields)
            <x-kompass::block.download-item :itemfield="$itemfields" />
        @endforeach

        <img-block wire:click="selectitem('addMedia',0,'download',{{ $itemblocks->id }})"
            class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 rounded w-full text-gray-400 aspect-[4/3] ">
            <x-tabler-file-download class="h-[4rem] w-[4rem] stroke-[1.5]" />
        </img-block>
    </div>
</div>