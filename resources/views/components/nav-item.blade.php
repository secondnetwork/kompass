@props(['itemblocks' => ''])

{{-- Block settings offcanvas. The controls shown per block type come from the
     central block-type registry; each control is an anonymous component under
     components/block-controls/. --}}
<div x-data="{ open: false }" class="relative inline-block">

    <button type="button" @click="open = true"
        class="flex items-center justify-center size-5 md:size-6 cursor-pointer transition-colors"
        title="{{ __('Settings') }}">
        <x-tabler-adjustments class="cursor-pointer stroke-current size-5 md:size-6 text-stone-500" />
    </button>

    <x-kompass::offcanvas :w="'w-1/3'">
        <x-slot name="button">
            <h4 class="font-bold text-lg">{{ __('Block Settings') }}</h4>
        </x-slot>

        <x-slot name="body">
            <x-kompass::block-settings-header :itemblocks="$itemblocks" />

            @foreach (block_registry()->controls($itemblocks->type) as $control)
                <x-dynamic-component :component="'kompass::block-controls.'.$control" :itemblocks="$itemblocks" />
            @endforeach
        </x-slot>
    </x-kompass::offcanvas>
</div>
