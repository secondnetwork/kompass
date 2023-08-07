@props([
    'key' => '',
    'items' => '',
    'fields' => '',
    'page' => '',
    'class' => '',
])

@foreach ($items as $key => $item)

<input wire:model="items.{{ $key }}.url" type="text">
<input wire:model="items.{{ $key }}.iconclass" type="text">target
@endforeach

