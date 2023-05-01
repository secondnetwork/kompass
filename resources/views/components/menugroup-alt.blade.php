@props([
    'key' => '',
    'items' => '',
    'fields' => '',
    'page' => '',
    'class' => '',
])

@foreach ($items as $key => $item)

<input wire:model.defer="items.{{ $key }}.url" type="text">
<input wire:model.defer="items.{{ $key }}.iconclass" type="text">target
@endforeach

