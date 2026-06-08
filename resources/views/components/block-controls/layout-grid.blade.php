@props(['itemblocks'])

{{-- Container column grid (group / accordiongroup). --}}
<x-kompass::settings-section :title="__('Layout Grid')">
    <div class="flex items-center gap-1">
        @foreach ([1, 2, 3, 4, 5] as $gridNumber)
            <span class="cursor-pointer rounded p-0.5 transition-colors {{ $itemblocks->layoutgrid == $gridNumber ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                wire:click="updateLayoutGrid({{ $itemblocks->id }}, {{ $gridNumber }})">
                @svg('tabler-square-number-'.$gridNumber, $itemblocks->layoutgrid == $gridNumber ? 'stroke-blue-500' : '')
            </span>
        @endforeach
    </div>
</x-kompass::settings-section>
