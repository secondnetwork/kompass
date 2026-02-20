@component('kompass::mail.html.layout')
{{-- Header --}}
@slot('header')
    @component('kompass::mail.html.header', ['url' => config('app.url')])
    @endcomponent
@endslot

{{-- Body --}}

# {{__('Hello')}} 👋,

**{{__('You are receiving this email because we received a password reset request for your account.')}}**

@component('kompass::mail.html.button', ['url' => $url, 'color' => 'primary'])
{{__('Reset Password')}}
@endcomponent

{{__('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')])}}

{{__('If you did not request a password reset, no further action is required.')}}


{{-- Subcopy --}}
@slot('subcopy')
    @component('kompass::mail.html.subcopy')
    @endcomponent
@endslot


{{-- Footer --}}
@slot('footer')
    @component('kompass::mail.html.footer')
    @endcomponent
@endslot
@endcomponent
