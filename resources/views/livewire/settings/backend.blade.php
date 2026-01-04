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
         <label class="text-base-content font-bold text-sm block">{{ __('Admin Copyright') }}</label>
           <input type="text" wire:model.live="admincopyright" class="input input-bordered w-full mt-1">
    </div>

</div>