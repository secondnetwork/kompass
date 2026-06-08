@props([
    'itemblocks' => '',
    'fields' => '',
    'page' => '',
    'class' => '',
    'cssclassname' => '',
])

<div class="{{ !in_array($itemblocks->type, ['gallery', 'video']) ? 'grid grid-cols-' . $itemblocks->grid . ' gap-6' : 'grid gap-6' }}">

    @switch($itemblocks->type)
        @case('video')
            <x-kompass::block.video :itemblocks="$itemblocks" />
        @break

        @case('gallery')
            <x-kompass::block.gallery :itemblocks="$itemblocks" />
        @break

        @default
            @foreach ($itemblocks->datafield as $item)
                <div wire:key="datafield-{{ $item->id }}" class="col-span-1 md:col-span-{{ $item->grid ?? '1' }} ">
                    <x-dynamic-component :component="'kompass::'.field_registry()->fieldComponent($item->type)" :itemfield="$item" />
                </div>
            @endforeach
        @endswitch

    </div>

    {{-- @foreach ($fields as $field)
            <div wire:sort:item="{{ $field->id }}" wire:key="field-item-{{ $field->id }}" class="col-span-1 md:col-span-{{ $field->grid ?? '1' }} ">
                 @livewire('field-editor', ['fieldId' => $field->id], key('field-editor-'.$field->id))
            </div>
        @endforeach --}}
    {{-- @dump($itemblocks->toArray()) --}}
