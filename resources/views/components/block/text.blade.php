@props([
    'itemfield' => '',
])
<div class="w-full">
    <input 
        type="text" 
        value="{{ $itemfield->data }}" 
        wire:blur="updateDatafield({{ $itemfield->id }}, $event.target.value)"
        class="input input-sm input-bordered w-full focus:input-primary"
        placeholder="..."
    />
</div>