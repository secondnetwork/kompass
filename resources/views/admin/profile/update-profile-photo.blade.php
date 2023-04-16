<x-kompass::form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
      
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 flex">
                <!-- Profile Photo File Input -->
                <input type="file" class="hidden"
                            wire:model="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />



                <!-- Current Profile Photo -->
                <div class="relative rounded-full w-20 h-20 flex items-center justify-center object-cover"" x-show="! photoPreview" x-on:click.prevent="$refs.photo.click()">
                    <span class="absolute inset-0 z-0 flex items-center justify-center text-[#36424A] bg-[#FFA700] rounded-full text-base">
                    {{ nameWithLastInitial(auth()->user()->name) }}
                  </span>
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ $this->user->name }}" class="absolute rounded-full h-20 w-20 z-10 items-center justify-center flex">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                {{-- <x-kompass::secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-kompass::secondary-button> --}}

                @if ($this->user->profile_photo_path)


                     <x-tabler-trash class="cursor-pointer stroke-current text-red-500 " wire:click="deleteProfilePhoto" />
        
                @endif

                <x-kompass::input-error for="photo" class="mt-2" />
            </div>

    

        <!-- Name -->
        <div class="col-span-6 ">
            <x-kompass::label for="name" value="{{ __('Name') }}" />
            <x-kompass::input id="name" type="text" class="mt-1 block w-full" wire:model.defer="state.name" autocomplete="name" />
            <x-kompass::input-error for="name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 ">
            <x-kompass::label for="email" value="{{ __('Email') }}" />
            <x-kompass::input id="email" type="email" class="mt-1 block w-full" wire:model.defer="state.email" />
            <x-kompass::input-error for="email" class="mt-2" />
        </div>
    </x-slot>


    <x-slot name="actions">
        <x-kompass::action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-kompass::action-message>

        <x-kompass::button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-kompass::button>
    </x-slot>
</x-kompass::form-section>
