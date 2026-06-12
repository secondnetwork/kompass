@props(['itemblocks'])

@php $alignment = $itemblocks->alignment ?? ''; @endphp

<x-kompass::settings-section :title="__('Alignment')">
    <div class="flex items-center gap-1">
        @foreach (['align-left' => 'tabler-align-left', 'align-center' => 'tabler-align-center', 'align-right' => 'tabler-align-right'] as $alignValue => $iconName)
            <span class="cursor-pointer rounded p-0.5 transition-colors {{ $alignment == $alignValue ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                wire:click="saveset({{ $itemblocks->id }},'alignment', '{{ $alignValue }}')">
                @svg($iconName, $alignment == $alignValue ? 'stroke-blue-500' : '')
            </span>
        @endforeach
    </div>
</x-kompass::settings-section>
