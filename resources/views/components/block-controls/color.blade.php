@props(['itemblocks'])

<x-kompass::settings-section :title="__('Block Color')">
    <x-kompass::block-color :itemblocks="$itemblocks" />
</x-kompass::settings-section>
