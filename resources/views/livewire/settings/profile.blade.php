
<section class="w-full" x-data="{ tab: 'profile' }">
    <div class="relative my-4 w-full">
        <x-kompass::heading level="3">{{ __('Account Settings') }}</x-kompass::heading>
        <x-kompass::subheading size="lg" class="mb-6">{{ __('Manage your profile and account settings') }}</x-kompass::subheading>
    </div>

    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    {{-- Tab Navigation --}}
    <div class="border-b border-base-300 mb-8">
        <nav class="flex -mb-px space-x-8" aria-label="Tabs">
            <a href="#_" @click.prevent="tab = 'profile'"
                :class="{
                    'border-indigo-500 text-indigo-600': tab === 'profile',
                    'border-transparent text-base-content/70 hover:border-gray-300 hover:text-gray-700': tab !== 'profile'
                }"
                class="px-1 py-4 text-sm font-medium whitespace-nowrap border-b-2 flex items-center gap-2">
                <x-tabler-user class="size-4" />
                {{ __('Profile') }}
            </a>
            <a href="#_" @click.prevent="tab = 'password'"
                :class="{
                    'border-indigo-500 text-indigo-600': tab === 'password',
                    'border-transparent text-base-content/70 hover:border-gray-300 hover:text-gray-700': tab !== 'password'
                }"
                class="px-1 py-4 text-sm font-medium whitespace-nowrap border-b-2 flex items-center gap-2">
                <x-tabler-lock class="size-4" />
                {{ __('Password') }}
            </a>
            <a href="#_" @click.prevent="tab = 'appearance'"
                :class="{
                    'border-indigo-500 text-indigo-600': tab === 'appearance',
                    'border-transparent text-base-content/70 hover:border-gray-300 hover:text-gray-700': tab !== 'appearance'
                }"
                class="px-1 py-4 text-sm font-medium whitespace-nowrap border-b-2 flex items-center gap-2">
                <x-tabler-palette class="size-4" />
                {{ __('Appearance') }}
            </a>
            @if (method_exists(auth()->user(), 'hasPasskeysEnabled'))
            <a href="#_" @click.prevent="tab = 'passkeys'"
                :class="{
                    'border-indigo-500 text-indigo-600': tab === 'passkeys',
                    'border-transparent text-base-content/70 hover:border-gray-300 hover:text-gray-700': tab !== 'passkeys'
                }"
                class="px-1 py-4 text-sm font-medium whitespace-nowrap border-b-2 flex items-center gap-2">
                <x-tabler-fingerprint class="size-4" />
                {{ __('Passkeys') }}
            </a>
            @endif
        </nav>
    </div>

    {{-- Profile Tab --}}
    <div x-show="tab === 'profile'" x-cloak class="max-w-lg">
        <div class="w-full pb-6">
            <span class="text-xl font-semibold">{{ __('Profile Information') }}</span>
            <p class="text-base-content/70">{{ __('Update your account\'s profile information and email address.') }}</p>
        </div>

        <div class="rounded-lg p-6 mb-6 flex items-center gap-6 border border-base-300">
            <x-kompass::elements.avatar
                :user="auth()->user()"
                size="w-24"
                clickable
                wire:model.live="photo" />

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
            <x-kompass::input wire:model="name" type="text" :label="__('Name')" :value="$name" name="name" required autocomplete="name" />
            <x-kompass::input wire:model="email" type="email" :label="__('Email')" :value="$email" name="email" required autocomplete="email" />

            <div class="flex items-center w-full">
                <button variant="primary" type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            </div>
            <x-kompass::action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-kompass::action-message>
        </form>
    </div>

    {{-- Password Tab --}}
    <div x-show="tab === 'password'" x-cloak class="max-w-lg">
        <div class="w-full pb-6">
            <span class="text-xl font-semibold">{{ __('Update Password') }}</span>
            <p class="text-base-content/70">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
        </div>

        <form wire:submit="updatePassword" class="space-y-6">
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

            <div class="flex items-center">
                <button variant="primary" type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            </div>
            <x-kompass::action-message class="me-3" on="password-updated">
                {{ __('Saved.') }}
            </x-kompass::action-message>
        </form>
    </div>

    {{-- Appearance Tab --}}
    <div x-show="tab === 'appearance'" x-cloak class="max-w-lg">
        <div class="w-full pb-6">
            <span class="text-xl font-semibold">{{ __('Appearance') }}</span>
            <p class="text-base-content/70">{{ __('Choose how Kompass looks for you. Light is the default — switch to dark for low-light environments.') }}</p>
        </div>

        <form wire:submit="updateAppearance"
              x-data="{ theme: @entangle('theme') }"
              x-init="$watch('theme', v => document.documentElement.setAttribute('data-theme', v))"
              class="space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <label class="cursor-pointer">
                    <input type="radio" value="light" wire:model.live="theme" class="peer sr-only">
                    <div class="border-2 border-base-300 peer-checked:border-primary rounded-xl p-4 hover:border-base-content/40 transition">
                        <div class="aspect-[4/3] rounded-lg bg-white border border-base-300 flex flex-col p-2 gap-1 mb-3">
                            <div class="h-1.5 w-8 bg-slate-300 rounded"></div>
                            <div class="h-1 w-10 bg-slate-200 rounded"></div>
                            <div class="mt-auto h-2 w-6 bg-slate-400 rounded"></div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold">{{ __('Light') }}</span>
                            <x-tabler-sun class="size-4 text-base-content/60" />
                        </div>
                    </div>
                </label>

                <label class="cursor-pointer">
                    <input type="radio" value="dark" wire:model.live="theme" class="peer sr-only">
                    <div class="border-2 border-base-300 peer-checked:border-primary rounded-xl p-4 hover:border-base-content/40 transition">
                        <div class="aspect-[4/3] rounded-lg bg-slate-900 border border-slate-700 flex flex-col p-2 gap-1 mb-3">
                            <div class="h-1.5 w-8 bg-slate-600 rounded"></div>
                            <div class="h-1 w-10 bg-slate-700 rounded"></div>
                            <div class="mt-auto h-2 w-6 bg-slate-400 rounded"></div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold">{{ __('Dark') }}</span>
                            <x-tabler-moon class="size-4 text-base-content/60" />
                        </div>
                    </div>
                </label>
            </div>

            <div class="flex items-center">
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            </div>
            <x-kompass::action-message class="me-3" on="appearance-updated">
                {{ __('Saved.') }}
            </x-kompass::action-message>
        </form>
    </div>

    {{-- Passkeys Tab --}}
    @if (method_exists(auth()->user(), 'hasPasskeysEnabled'))
    <div x-show="tab === 'passkeys'" x-cloak class="max-w-lg">
        @livewire(\Secondnetwork\Kompass\Livewire\Settings\PasskeyManagement::class)
    </div>
    @endif

</section>
