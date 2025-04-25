
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


    <div class="w-full space-y-6 ">
     
        <span class="text-xl font-semibold">{{ __('Profile Information') }}</span>
        <p class="text-base-content/70"> {{ __('Update your account\'s profile information and email address.') }}</p>

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
        <div class="flex items-center  w-full">
        <button variant="primary" type="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </div>
    </form>




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
    </form>
</div>
</section>
