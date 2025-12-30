@props([
    'itemfield' => '',
])
<div class="flex items-center gap-2">
    <input 
        type="checkbox" 
        class="toggle toggle-primary" 
        {{ $itemfield->data ? 'checked' : '' }}
        wire:change="selectedAction({{ $itemfield->id }})" 
    />
    <span class="text-sm font-medium text-gray-700">{{ $itemfield->name }}</span>
</div>