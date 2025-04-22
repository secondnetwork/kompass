<section class="w-full">

        @if (session('status'))
            <div>{{ session('status') }}</div>
        @endif
<div class="grid-2-3 gap-4">
    <div class="my-6 w-full space-y-6">
     
        <h3>{{ __('Profile Information') }}</h3>
        {{ __('Update your account\'s profile information and email address.') }}

    </div>
    <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">


        <x-kompass::input wire:model="name"  type="text" :label="__('Name')" :value="$name" name="name" required autocomplete="name" />
        <x-kompass::input wire:model="email" type="email" :label="__('Email')" :value="$email" name="email" required autocomplete="email" />

        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
            {{-- <div>
                <x-text class="mt-4">
                    {{ __('Your email address is unverified.') }}

                    <x-button variant="link" :formaction="route('verification.store')" name="_method" value="post">
                        {{ __('Click here to re-send the verification email.') }}
                    </x-button>
                </x-text>

                @if (session('status') === 'verification-link-sent')
                    <x-text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </x-text>
                @endif 
            </div> --}}
        @endif

<button variant="primary" type="submit" class="w-full btn primary">{{ __('Save') }}</button>
<x-kompass::action-message class="me-3" on="profile-updated">
    {{ __('Saved.') }}
</x-kompass::action-message>
</form>

</div>

<div class="grid-2-3 gap-4">
    <div class="my-6 w-full space-y-6">
        <h3>{{ __('Update Password') }}</h3>
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
   
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
                <button variant="primary" type="submit" class="w-full btn primary">{{ __('Save') }}</button>
            </div>

            <x-kompass::action-message class="me-3" on="password-updated">
                {{ __('Saved.') }}
            </x-kompass::action-message>
        </div>
    </form>
</div>
   
</section>
