@props([
    'itemfield' => '',
])

<x-kompass::color-picker
    wire:key="field-color-{{ $itemfield->id }}"
    :value="$itemfield->data ?? ''"
    @changed="$wire.updateDatafield({{ $itemfield->id }}, $event.detail)"
/>
