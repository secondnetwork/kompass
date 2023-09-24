<div>
    <x-kompass::form.input wire:model="name" label="Name" type="text" class="mt-1 block w-full"   />
    {{-- <x-kompass::form.textarea wire:model="blockarray.meta_description" id="name" name="title" label="Description" type="text" class="mt-1 block w-full"   /> --}}
    <div class="modal-footer mt-auto">
        <button wire:click="saveBlock" class="btn btn-primary">{{__('Save')}}</button>
      </div>
</div>