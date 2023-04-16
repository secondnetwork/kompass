@extends('kompass::admin.layouts.app')

@section('content')
<div class="pt-10">
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <div class="py-8">

   

    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updateProfileInformation()))
        @livewire('update-profile-photo') 
    @endif
  
</div>
<div class="py-8">
        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
            @livewire('update-password-form')
        @endif
    </div>
{{-- <div class="main_grid">
    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::twoFactorAuthentication()))
        @include('kompass::admin.profile.two-factor-authentication-form')
    @endif
</div> --}}
</div>
@endsection
