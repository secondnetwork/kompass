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

    {{ Vite::useBuildDirectory('content')->withEntryPoints(['resources/css/main.css']) }}

    @livewireStyles

    <style>
        [x-cloak] {display: none !important;}
    </style>

</head>

<body class="{{ str_replace('.', '-', Route::currentRouteName()) }}">

    <header>

        <div class="md:flex gap-4 py-8 justify-between">
            <div>LOGO BRAND</div>
            <div class="flex gap-4">
                <livewire:menu name="main">
                    <div class="pt-3 md:pt-0">
                        @if (Route::has('login'))
                            <div class="hidden top-0 right-0  sm:block">
                                @auth
                                    <a href="{{ url('/admin/dashboard') }}" class="text-gray-700 flex gap-1"><x-tabler-settings-2/>Admin Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="text-gray-700 flex gap-1"><x-tabler-lock/>Login</a>

                                    @if (Route::has('register'))
                                        {{-- <a href="{{ route('register') }}" class="text-gray-700">Register</a> --}}
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
            </div>

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
                &COPY; {{ date('Y') }} {{ setting('footer.copytext') }}
            </div>
            <livewire:menu name="footer">

        </div>
    </footer>

    <div @click="window.scrollTo({top: 0, behavior: 'smooth'})" x-data="{ gotop: false }" class="bg-gray-900/50 p-2 h-10 w-10 rounded shadow fixed cursor-pointer right-8 transition-all"
    :class="!gotop ? '-bottom-10 opacity-0' : 'transition duration-300 bottom-8 opacity-100'">
        <button
        @scroll.window="gotop = (window.pageYOffset > 40) ? true : false"

        class=""><x-tabler-arrow-big-up-lines class="text-white"/></button>
    </div>

    @livewireScripts
    {{ Vite::useBuildDirectory('content')->withEntryPoints(['resources/js/main.js']) }}
    @stack('scripts')
</body>

</html>
