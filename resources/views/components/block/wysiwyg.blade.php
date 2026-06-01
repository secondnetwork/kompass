{{-- @props(['itemfield']) --}}

    <div class="col-span-{{ $itemfield->grid }}" style="order: {{ $itemfield->order }} ">
        <livewire:kompass-editor
            wire:key="kompass-editor-{{ $itemfield->id }}"
            :editor-id="$itemfield->id"
            :value="$itemfield->data"
            :placeholder="__('write something...')"
            :read-only="false"
        />
    </div>