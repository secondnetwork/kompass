@props([
    'itemfield' => '',
])
@php
    $data = $itemfield->data ?? [];
@endphp
<div class="grid gap-2 p-3 border border-base-300 rounded-lg bg-base-100 shadow-sm">
    <x-kompass::input 
        type="text" 
        value="{{ $data['url'] ?? '' }}" 
        wire:blur="updateDatafieldArray({{ $itemfield->id }}, 'url', $event.target.value)"
        placeholder="URL (https://...)"
        label="URL"
         class="w-full"
    />
    
    <div class="flex gap-2 items-end">
        <x-kompass::input 
            type="text" 
            value="{{ $data['title'] ?? '' }}" 
            wire:blur="updateDatafieldArray({{ $itemfield->id }}, 'title', $event.target.value)"
            placeholder="Link Title"
            label="Link Title"
            class="w-1/2"
        />
        

            <x-kompass::select 
                wire:model="data.target"
                wire:change="updateDatafieldArray({{ $itemfield->id }}, 'target', $event.target.value)"
                label=" "
                 class="w-1/2"
                :searchable="false"
                :options="[
                    ['name' => __('Same tab'), 'id' => '_self'],
                    ['name' => __('New tab'), 'id' => '_blank'],
                ]"
            />

    </div>

    <div class="flex items-center gap-2">
        <button 
            type="button" 
            wire:click="$dispatch('open-icon-picker', { fieldId: {{ $itemfield->id }} })" 
            class="btn btn-outline flex items-center gap-2"
        >
            @if(!empty($data['iconclass']))
                <x-icon :name="$data['iconclass']" class="h-4 w-4" />
            @else
                <x-tabler-icons class="h-4 w-4" />
            @endif
            Icon wählen
        </button>
        @if(!empty($data['iconclass']))
            <span class="text-xs">{{ $data['iconclass'] }}</span>
            <button type="button" wire:click="updateDatafieldArray({{ $itemfield->id }}, 'iconclass', '')" class="btn btn-ghost btn-xs">
                <x-tabler-x class="h-3 w-3" />
            </button>
        @endif
        <div class="w-28">
            <x-kompass::select 
                wire:model="data.iconposition"
                wire:change="updateDatafieldArray({{ $itemfield->id }}, 'iconposition', $event.target.value)"
                label=" "
                :searchable="false"
                :options="[
                    ['name' => __('left'), 'id' => 'left'],
                    ['name' => __('right'), 'id' => 'right'],
                ]"
                
            />
        </div>
    </div>
</div>
