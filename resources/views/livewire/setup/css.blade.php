<div x-data="{
    css: @entangle('css')
}"
x-init=""
@update-css-code.window="css=event.detail.value;"
    >
    <div class="mb-3 w-full" wire:ignore>
        <textarea id="css-editor" class="w-full min-h-[350px] rounded-xl border border-zinc-200 overflow-hidden" x-model="css"></textarea>
    </div>
    <button class="btn" wire:click="update">Update</button>
    {{-- <x-kompass::setup.button wire:click="update">Update</x-kompass::setup.button> --}}
</div>