@component('kompass::mail.html.layout')
{{-- Header --}}
@slot('header')
    @component('kompass::mail.html.header', ['url' => config('app.url')])
        <!-- header here -->
    @endcomponent
@endslot

{{-- Body --}}
<!-- Body here -->


# {{__('Hello')}} 👋  {{ $datamessage['name'] ?? ''}},

**{{__('you have been granted access to Kompass admin panel')}}.**

{{__('With your email')}}: **{{ $datamessage['email'] ?? ''}}**

{{__('and with the password')}}: **{{ $datamessage['password'] ?? ''}}** 

**{{__('Log in at')}} <a href="{{env('APP_URL')}}/login" target="_blank">{{env('APP_URL')}}/login</a>, {{__('to access')}}.**



{{__('Have fun with it')}}.


{{-- Subcopy --}}
@slot('subcopy')
    @component('kompass::mail.html.subcopy')
        <!-- subcopy here -->
    @endcomponent
@endslot


{{-- Footer --}}
@slot('footer')
    @component('kompass::mail.html.footer')
        <!-- footer here -->
    @endcomponent
@endslot
@endcomponent




