@props([
    'itemfield' => '',
])

<x-kompass::color-picker
    :value="$itemfield->data ?? ''"
    @changed="$wire.updateDatafield({{ $itemfield->id }}, $event.detail)"
/>
