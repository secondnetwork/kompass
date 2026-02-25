@if($this->FormIconPicker ?? false)
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click="resetIconPicker">
    <div class="bg-base-100 rounded-lg p-6 w-[90vw] max-h-[90vh] overflow-hidden flex flex-col" wire:click.stop>
        <div class="flex justify-between items-center mb-4">
            <button wire:click="resetIconPicker" class="btn btn-ghost btn-sm">
                <x-tabler-x class="w-5 h-5" />
            </button>
        </div>

        <input 
            type="text" 
            wire:model.live="iconSearch" 
            placeholder="Icon suchen..."
            class="input input-bordered w-full mb-4"
            autofocus
        />

        @if(count($this->filteredIcons ?? []) > 0)
            <div class="flex-1 overflow-y-auto border border-base-300 rounded p-2">
                <div class="grid grid-cols-6 gap-2">
                    @foreach($this->filteredIcons ?? [] as $iconItem)
                        <button 
                            wire:click="selectIcon('{{ $iconItem['name'] }}')"
                            class="flex flex-col items-center gap-1 p-2 hover:bg-base-200 rounded transition-colors"
                        >
                            <x-icon :name="$iconItem['full_name']" class="w-6 h-6" />
                            <span class="text-[9px] truncate max-w-full text-base-content/70">{{ $iconItem['name'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
            <div class="mt-2 text-sm text-base-content/60">
                {{ count($this->filteredIcons ?? []) }} Icons gefunden
            </div>
        @else
            <p class="text-center text-gray-500 py-8">
                @if($this->iconSearch ?? '')
                    Keine Icons gefunden für: {{ $this->iconSearch }}
                @else
                    Keine Icons verfügbar
                @endif
            </p>
        @endif
    </div>
</div>
@endif
