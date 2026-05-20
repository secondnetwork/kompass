@props([
    'itemblocks' => '',
    'fields' => '',
    'page' => '',
    'class' => '',
    'cssclassname' => '',
])

<div class="{{ !in_array($itemblocks->type, ['gallery', 'video']) ? 'grid grid-cols-' . $itemblocks->grid . ' gap-6' : '' }}">

<livewire:editable-name :itemblocks="$itemblocks" :key="'editable-block-name-'.$itemblocks->id" class="text-4xl py-6" />

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
                    @switch($item['type'])
                        @case('true_false')
                            <x-kompass::block.true_false :itemfield="$item" />
                        @break

                        @case('image')
                            <x-kompass::block.image :itemfield="$item" />
                        @break

                        @case('wysiwyg')
                            <x-kompass::block.wysiwyg :itemfield="$item" />
                        @break

                        @case('link')
                            <x-kompass::block.link :itemfield="$item" />
                        @break

                        @case('file')
                            <x-kompass::block.file :itemfield="$item" />
                        @break

                        @case('color')
                            <x-kompass::block.color :itemfield="$item" />
                        @break

                        @default
                            <x-kompass::block.text :itemfield="$item" />
                    @endswitch
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
