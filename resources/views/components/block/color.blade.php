@props([
    'itemfield' => '',
])
<div class="join w-full">
    <div class="join-item border border-base-300 flex items-center px-2 bg-base-100">
        <input 
            type="color" 
            value="{{ $itemfield->data ?? '#000000' }}" 
            wire:change="updateDatafield({{ $itemfield->id }}, $event.target.value)"
            class="w-6 h-6 cursor-pointer rounded overflow-hidden border-none p-0 bg-transparent"
        />
    </div>
    <input 
        type="text" 
        value="{{ $itemfield->data }}" 
        wire:blur="updateDatafield({{ $itemfield->id }}, $event.target.value)"
        class="input input-sm input-bordered join-item w-full font-mono focus:input-primary text-xs"
        placeholder="#000000"
    />
</div>