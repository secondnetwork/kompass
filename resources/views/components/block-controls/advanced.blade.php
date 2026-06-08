@props(['itemblocks'])

<x-kompass::settings-section :title="__('Advanced')">
    <div class="flex items-center gap-2">
        <span class="text-xs text-neutral-500 w-28 shrink-0 leading-tight">{{ __('Classname') }}</span>
        <x-kompass::block-meta-input :itemblocks="$itemblocks" meta-key="css-classname" placeholder="my-class" />
    </div>
    <div class="flex items-center gap-2">
        <span class="text-xs text-neutral-500 w-28 shrink-0 leading-tight">{{ __('ID') }}</span>
        <x-kompass::block-meta-input :itemblocks="$itemblocks" meta-key="id-anchor" placeholder="section-id" />
    </div>
</x-kompass::settings-section>
