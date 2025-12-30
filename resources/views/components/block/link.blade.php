@props([
    'itemfield' => '',
])
@php
    $data = $itemfield->data ?? [];
@endphp
<div class="grid gap-2 p-3 border border-base-300 rounded-lg bg-base-100 shadow-sm">
    <div class="join w-full">
        <div class="join-item border border-base-300 flex items-center px-2 bg-base-200/50">
            <x-tabler-link class="h-4 w-4 text-base-content/50 shrink-0" />
        </div>
        <input 
            type="text" 
            value="{{ $data['url'] ?? '' }}" 
            wire:blur="updateDatafieldArray({{ $itemfield->id }}, 'url', $event.target.value)"
            class="input input-sm input-bordered join-item w-full focus:input-primary text-xs"
            placeholder="URL (https://...)"
        />
    </div>
    
    <div class="flex gap-2">
        <div class="join flex-1">
            <div class="join-item border border-base-300 flex items-center px-2 bg-base-200/50">
                <x-tabler-pencil class="h-3.5 w-3.5 text-base-content/50 shrink-0" />
            </div>
            <input 
                type="text" 
                value="{{ $data['title'] ?? '' }}" 
                wire:blur="updateDatafieldArray({{ $itemfield->id }}, 'title', $event.target.value)"
                class="input input-xs input-bordered join-item w-full focus:input-primary"
                placeholder="Link Title"
            />
        </div>
        
        <div class="join w-32">
            <div class="join-item border border-base-300 flex items-center px-2 bg-base-200/50">
                <x-tabler-external-link class="h-3.5 w-3.5 text-base-content/50 shrink-0" />
            </div>
            <select 
                wire:change="updateDatafieldArray({{ $itemfield->id }}, 'target', $event.target.value)"
                class="select select-xs select-bordered join-item w-full focus:select-primary text-[10px]"
            >
                <option value="_self" @if(($data['target'] ?? '_self') == '_self') selected @endif>Same Tab</option>
                <option value="_blank" @if(($data['target'] ?? '') == '_blank') selected @endif>New Tab</option>
            </select>
        </div>
    </div>
</div>