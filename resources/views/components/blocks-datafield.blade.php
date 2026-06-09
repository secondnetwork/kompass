@props([
    'itemblocks' => '',
    'fields' => '',
    'page' => '',
    'class' => '',
    'cssclassname' => '',
    'relationshipSearch' => [],
])

@php
    $isBuiltinGallery      = block_registry()->isBuiltin($itemblocks->type) && $itemblocks->type === 'gallery';
    $isBuiltinVideo        = block_registry()->isBuiltin($itemblocks->type) && $itemblocks->type === 'video';
    $isBuiltinRelationship = block_registry()->isBuiltin($itemblocks->type) && $itemblocks->type === 'relationship';
    $isSpecial             = $isBuiltinGallery || $isBuiltinVideo || $isBuiltinRelationship;
@endphp
<div class="{{ $isSpecial ? 'grid gap-6' : 'grid grid-cols-' . $itemblocks->grid . ' gap-6' }}">

    @if ($isBuiltinVideo)
        <x-kompass::block.video :itemblocks="$itemblocks" />
    @elseif ($isBuiltinGallery)
        <x-kompass::block.gallery :itemblocks="$itemblocks" />
    @elseif ($isBuiltinRelationship)
        <x-kompass::block.relationship :itemblocks="$itemblocks" :search="$relationshipSearch[$itemblocks->id] ?? ''" />
    @else
        @foreach ($itemblocks->datafield as $item)
            <div wire:key="datafield-{{ $item->id }}" class="col-span-1 md:col-span-{{ $item->grid ?? '1' }} ">
                <x-dynamic-component :component="'kompass::'.field_registry()->fieldComponent($item->type)" :itemfield="$item" />
            </div>
        @endforeach
    @endif

</div>

    {{-- @foreach ($fields as $field)
            <div wire:sort:item="{{ $field->id }}" wire:key="field-item-{{ $field->id }}" class="col-span-1 md:col-span-{{ $field->grid ?? '1' }} ">
                 @livewire('field-editor', ['fieldId' => $field->id], key('field-editor-'.$field->id))
            </div>
        @endforeach --}}
    {{-- @dump($itemblocks->toArray()) --}}
