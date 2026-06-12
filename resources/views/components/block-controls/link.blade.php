@props(['itemblocks'])

<x-kompass::settings-section :title="__('Link')">
    <x-kompass::block-meta-input :itemblocks="$itemblocks" meta-key="link-url" placeholder="https://example.com" />
</x-kompass::settings-section>
