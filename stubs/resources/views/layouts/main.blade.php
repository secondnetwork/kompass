<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }} ">
    <link rel="apple-touch-icon" href="{{ asset('favicon/apple-touch-icon.png') }} ">
    <link rel="icon" href="{{ asset('favicon/favicon.ico') }}" sizes="any">
    {{-- <link rel="icon" href="{{ asset('favicon/safari-pinned-tab.svg') }}" type="image/svg+xml"> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="url" content="{{ url('/') }}">
    <meta name="assets-path" content="{{ route('kompass_asset') }}" />
    <title>@hasSection('title')@yield('title') |@endif {{ config('app.name') }}</title>
    @hasSection('seo')
    @yield('seo')
    @endif

    {{ Vite::useBuildDirectory('content')->withEntryPoints(['resources/sass/main.scss']) }}

    @livewireStyles

    <style>
        [x-cloak] {display: none !important;}
    </style>

</head>

<body class="{{ str_replace('.', '-', Route::currentRouteName()) }}">

    @if (Route::has('login'))
        <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            @auth
                <a href="{{ url('/admin/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">to Admin dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
                @endif
            @endauth
        </div>
    @endif

    <header>
        <div class="md:flex gap-4 py-8">
            <livewire:menu name="main">
        </div>
        
    </header>

    <main>
        @isset($slot)
            {{ $slot }}
        @endisset
    </main>

    <div class="divider"></div>
    <footer>
        <div class="md:flex gap-4 py-8">
            <div class="copyright">
                &COPY; {{ date('Y') }} {{ setting('copytext') }}
            </div>

            <livewire:menu name="footer">

        </div>
    </footer>

    @livewireScripts
    {{ Vite::useBuildDirectory('content')->withEntryPoints(['resources/js/main.js']) }}

</body>

</html>
