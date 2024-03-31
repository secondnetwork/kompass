<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<!--
*   Kompass A Laravel CMS
*   Development and Design by secondnetwork
*   https://kompass.secondnetwork.de/
*
-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="manifest" href="{{ kompass_asset('favicon/manifest.webmanifest') }} ">
    <link rel="apple-touch-icon" href="{{ kompass_asset('favicon/apple-touch-icon.png') }} ">
    <link rel="icon" href="{{ kompass_asset('favicon/favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ kompass_asset('favicon/icon.svg') }}" type="image/svg+xml">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="url" content="{{ url('/') }}">
    <meta name="assets-path" content="{{ route('kompass_asset') }}"/>
    <meta name="theme-color" content="#ffa700" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#CF8700" media="(prefers-color-scheme: dark)"> 
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

@kompassCss

</head>
<body class="kompass-{{ str_replace(".","-", Route::currentRouteName()) }}">



<div class="grid grid-cols-11 h-screen items-center justify-center bg-gray-100">
<div class="grid col-start-1 lg:col-end-5 col-end-12 gap-y-8 p-12">
    <div class="logo w-[14rem]">
        @if (!empty(setting('global.admin-logo')))
        <img src="{{ setting('global.admin-logo') }}" alt="">
        @else
        <img src="{{ kompass_asset('kompass_logo.svg') }}" alt="">
        @endif
        
    
    </div>

        <main class="grid gap-y-8">
            @yield('content')
        </main>

    </div>

    <div class="hidden lg:grid col-start-5 col-end-12 bg-cover h-full bg-gray-900 bg-opacity-75" >    
        <div class="relative flex flex-col">
            <div class="bg-black opacity-30 absolute inset-0 z-10"></div>
        @if (!empty(setting('global.admin-bg-img')))
            @php
                $file = Secondnetwork\Kompass\Models\File::find(setting('global.admin-bg-img'));
            @endphp

            @if ($file)
                @if (Storage::disk('local')->exists('/public/' . $file->path . '/' . $file->slug . '.' . $file->extension))
                 <div class="bg-cover h-full absolute inset-0" style="background-image: url('{{ asset('storage' . $file->path . '/' . $file->slug . '.' . $file->extension) }}')"></div>
                @endif
            @endif
          
        @else
                 <div class="bg-cover h-full absolute inset-0" style="background-image: url('{{ kompass_asset('bg_login.jpg') }}')"></div>
        @endif    

        <footer class=" flex justify-between mt-auto w-full z-50">
            <div class="text-xs flex items-center text-white p-8">
                <x-tabler-copyright class="w-4" />{{ \Carbon\Carbon::now()->format('Y') }} {{ setting('global.copyright') ?? 'secondnetwork'}}
              </div>
            <div class="text-gray-300 text-xs p-8">
            @php $version = Kompass::getVersion(); @endphp
            @if (!empty($version))
            <strong>Kompass </strong> {{ $version }}
            @endif
            </div>
        </footer>
    </div>

    </div>

</div>
 



@kompassJs
@stack('scripts')

</body>
</html>