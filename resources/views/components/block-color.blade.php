@props(['itemblocks'])

<x-kompass::color-picker
    wire:key="block-color-{{ $itemblocks->id }}"
    :value="$itemblocks->getMeta('color') ?: ''"
    @changed="$wire.saveset({{ $itemblocks->id }}, 'color', $event.detail)"
/>
