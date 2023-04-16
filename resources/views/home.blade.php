<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="url" content="{{ url('/') }}">
    <meta name="assets-path" content="{{ route('kompass_asset') }}"/>

    <title>@hasSection('title') @yield('title') | @endif {{ config('app.name') }}</title>
        {{-- Social Share Open Graph Meta Tags --}}
    @if(isset($seo->title) && isset($seo->description) && isset($seo->image))
        <meta property="og:title" content="{{ $seo->title }}">
        <meta property="og:url" content="{{ Request::url() }}">
        <meta property="og:image" content="{{ $seo->image }}">
        <meta property="og:type" content="@if(isset($seo->type)){{ $seo->type }}@else{{ 'article' }}@endif">
        <meta property="og:description" content="{{ $seo->description }}">
        <meta property="og:site_name" content="{{ setting('site.title') }}">

        <meta itemprop="name" content="{{ $seo->title }}">
        <meta itemprop="description" content="{{ $seo->description }}">
        <meta itemprop="image" content="{{ $seo->image }}">

        @if(isset($seo->image_w) && isset($seo->image_h))
            <meta property="og:image:width" content="{{ $seo->image_w }}">
            <meta property="og:image:height" content="{{ $seo->image_h }}">
        @endif
    @endif

    <meta name="robots" content="index,follow">
    <meta name="googlebot" content="index,follow">

    @if(isset($seo->description))
        <meta name="description" content="{{ $seo->description }}">
    @endif

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