@props(['itemblocks'])

<x-kompass::color-picker
    :value="$itemblocks->getMeta('color') ?: ''"
    @changed="$wire.saveset({{ $itemblocks->id }}, 'color', $event.detail)"
/>
