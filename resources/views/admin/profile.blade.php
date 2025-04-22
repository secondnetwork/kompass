@extends('kompass::admin.layouts.app')

@section('content')
<div class="pt-10">
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <div class="py-8">

        <div>
            <x-kompass::input type="email" :label="__('Email')" :value="$user->email" name="email" required autocomplete="email" />

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
                    @endif --}}
                </div>
            @endif
        </div>

    {{-- @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updateProfileInformation()))
        @livewire('update-profile-photo') 
    @endif
  
</div>
<div class="py-8">
        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
            @livewire('update-password-form')
        @endif
    </div> --}}
{{-- <div class="main_grid">
    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::twoFactorAuthentication()))
        @include('kompass::admin.profile.two-factor-authentication-form')
    @endif
</div> --}}
</div>
@endsection
