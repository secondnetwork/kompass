<div x-data="click_to_edit()" class="w-11/12 flex items-center">

    <div x-data="{ id: '{{ $itemblocks->id }}', name: '{{ $itemblocks->name }}' }">

        <a @click.prevent @click="toggleEditingState" x-show="!isEditing"
        class="flex items-center select-none cursor-text" x-on:keydown.escape="isEditing = false">
        <span class="text-sm font-semibold" x-text="name"></span>
        </a>
        
        <div x-show="isEditing" class="flex items-center"  >
            <input type="text" class="border border-gray-400 px-1 py-1 text-sm font-semibold" x-model="name"
                wire:model.lazy="newName" x-ref="input" x-on:keydown.enter="isEditing = false"
                x-on:keydown.escape="isEditing = false"
                x-on:click.away="isEditing = false" wire:keydown.enter="savename">
            <span wire:click="savename" x-on:click="isEditing = false">
                <x-tabler-square-check class="cursor-pointer stroke-current h-6 w-6 text-green-600" />
            </span>
            <span x-on:click="isEditing = false">
                <x-tabler-square-x class="cursor-pointer stroke-current h-6 w-6 text-red-600" />
            </span>
        </div>
    
    </div>

</div>