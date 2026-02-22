<div class=" grid grid-cols-1 items-center  h-auto gap-4">

    <x-kompass::form.switch wire:model.live="registration_can_user" label="{{ __('User can register') }}" />

    <hr class="h-px w-full border-none bg-base-300">
    
    <x-kompass::upload-image 
        wire:model="adminlogo" 
        :image="$adminlogo" 
        delete-action="deleteImage()" 
        label="Admin Logo" 
    />

    <div class="mb-6 max-w-2xl">
        <x-kompass::form.input label="{{ __('Admin Copyright') }}" type="text" name="admincopyright" wire:model.live="admincopyright" />
    </div>

</div>