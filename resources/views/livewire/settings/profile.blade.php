
<section class="w-full">
    <div class="relative my-4 w-full">
        <x-kompass::heading  level="3">{{ __('Account Settings') }}</x-kompass::heading>
        <x-kompass::subheading size="lg" class="mb-6">{{ __('Manage your profile and account settings') }}</x-kompass::subheading>
    
        <div class="divider"></div>
    </div>
    
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <div class="max-w-lg">
        

        <div class="w-full pb-6">
            <span class="text-xl font-semibold">{{ __('Profile Information') }}</span>
            <p class="text-base-content/70"> {{ __('Update your account\'s profile information and email address.') }}</p>
        </div>

        <!-- Avatar Section -->
        <div class="rounded-lg p-6 mb-6 flex items-center gap-6 border border-base-300">
            <div class="avatar">
   
                <div class="relative rounded-full w-20 h-20 flex items-center justify-center object-cover""  x-on:click.prevent="$refs.photo.click()">
                  <span class="absolute inset-0 z-0 flex items-center justify-center text-[#36424A] bg-[#FFA700] rounded-full text-3xl">
                    {{ nameWithLastInitial(auth()->user()->name) }}
                  </span>

                    @if ($photo)
                        <img class="absolute rounded-full h-20 w-20 z-10 items-center justify-center flex" src="{{ $photo->temporaryUrl() }}" />
                    @else
                        <img class="absolute rounded-full h-20 w-20 z-10 items-center justify-center flex" src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" />
                    @endif
                    {{-- <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ $this->user->name }}" class="absolute rounded-full h-20 w-20 z-10 items-center justify-center flex"> --}}
                </div>


            </div>

            <div class="flex flex-col gap-2">
                <div class="flex items-center gap-2">
                    <label for="photo" class="btn btn-primary btn-sm">
                        <x-tabler-upload class="size-4 mr-1" />
                        {{ __('Upload photo') }}
                    </label>
                    <input type="file" id="photo" class="hidden" wire:model.live="photo" accept="image/*" />

                    @if (auth()->user()->profile_photo_path)
                        <button type="button" class="btn btn-error btn-outline btn-sm" wire:click="deleteProfilePhoto" wire:confirm="{{ __('Are you sure you want to delete your profile photo?') }}">
                            {{ __('Delete') }}
                        </button>
                    @endif
                </div>
                <p class="text-xs text-base-content/60">{{ __('Allowed JPG, GIF or PNG. Max size of 1MB') }}</p>
                <div wire:loading wire:target="photo" class="text-xs text-primary font-semibold">
                    {{ __('Uploading...') }}
                </div>
                @error('photo') <span class="text-error text-xs">{{ $message }}</span> @enderror
            </div>
        </div>


        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <x-kompass::input wire:model="name"  type="text" :label="__('Name')" :value="$name" name="name" required autocomplete="name" />
            <x-kompass::input wire:model="email" type="email" :label="__('Email')" :value="$email" name="email" required autocomplete="email" />

            <div class="flex items-center  w-full">
                <button variant="primary" type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            </div>
            <x-kompass::action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-kompass::action-message>
        </form>

        <div class="divider"></div>

        <div class="w-full space-y-6">
            <span class="text-xl font-semibold">{{ __('Update Password') }}</span>
            <p class="text-base-content/70">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
        </div>

        <form wire:submit="updatePassword" class="mt-6 space-y-6">
            <x-kompass::input
                wire:model="current_password"
                :label="__('Current Password')"
                type="password"
                required
                autocomplete="current-password"
            />
            <x-kompass::input
                wire:model="password"
                :label="__('New Password')"
                type="password"
                required
                autocomplete="new-password"
            />
            <x-kompass::input
                wire:model="password_confirmation"
                :label="__('Confirm Password')"
                type="password"
                required
                autocomplete="new-password"
            />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <button variant="primary" type="submit" class="w-full btn btn-primary">{{ __('Save') }}</button>
                </div>
            </div>
            <x-kompass::action-message class="me-3" on="password-updated">
                {{ __('Saved.') }}
            </x-kompass::action-message>
        </form>
    </div>
</section>
