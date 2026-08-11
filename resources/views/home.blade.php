<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="url" content="{{ url('/') }}">
    <meta name="assets-path" content="{{ route('kompass_asset') }}"/>

    @head

    <livewire:styles/>
    <link rel="stylesheet" href="{{ kompass_asset('css/app.css') }}">

    {{-- <img src="{{ kompass_asset('kompass_logo.svg')}}" alt=""> --}}
    
</head>
<body>

    <div class="box">

</div>
    @yield('content')
    @stack('scripts')
    {{ $slot }}
    {{-- <x-tabler-alert-circle class="icon-lg"/> --}}
  {{-- <x-social-instagram /><x-social-linkedin /><x-social-tiktok /><x-social-twitter /><x-social-vimeo /><x-social-xing /><x-social-youtube /> --}}
    <livewire:scripts/>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>