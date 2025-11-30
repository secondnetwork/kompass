<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

{{-- Assuming x-seo::meta uses settings internally or doesn't need direct changes here --}}
<x-seo::meta />

{{-- Favicon Links using setting() --}}
@if (setting('global.favicon_light_image_path'))
<link href="{{ url(setting('global.favicon_light_image_path')) }}" rel="icon" media="(prefers-color-scheme: light)" />
{{-- These manifest and apple-touch-icon links might be hardcoded or generated elsewhere, keep as is --}}
<link rel="manifest" href="{{ asset('favicon/site.webmanifest') }} ">
<link rel="apple-touch-icon" href="{{ asset('favicon/apple-touch-icon.png') }} ">
@endif
{{-- Optional dark mode favicon --}}
@if (setting('global.favicon_dark_image_path', ''))
<link href="{{ url(setting('global.favicon_dark_image_path')) }}" rel="icon" media="(prefers-color-scheme: dark)" />
@endif
{{-- Theme color meta tag --}}
<meta name="theme-color" content="{{ setting('global.favicon_theme_color', '#ffffff') }}">

<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="url" content="{{ url('/') }}">
<meta name="assets-path" content="{{ route('kompass_asset') }}" />

{{ Vite::useBuildDirectory('content')->withEntryPoints(['resources/css/main.css']) }}

@livewireStyles

<style>
    [x-cloak] {display: none !important;}
</style>

</head>

<body class="{{ str_replace('.', '-', Route::currentRouteName()) }}">

    <header>
        <div class="md:flex gap-4 py-8 justify-between">
            <div>
                {{-- Logo Component properties using setting() --}}
                <x-kompass::elements.logo
                    :height="setting('global.logo_height', '2')" {{-- Default height if not set --}}
                    :isImage="(setting('global.logo_type', 'text') == 'image')" {{-- Default type 'text' if not set --}}
                    :imageSrc="setting('global.logo_image_src', '')" {{-- Default empty string --}}
                    :svgString="setting('global.logo_svg_string', '')" {{-- Default empty string --}}
                />
            </div>
            <div class="flex gap-4">
                <div  class="animate-fade">


          <livewire:menu name="main" />
                </div>

                <div class="pt-3 md:pt-0">
                    @if (Route::has('login'))
                        <div class=" flex gap-4 ">
                            @auth
                                <a href="{{ url('/admin/dashboard') }}" class="text-gray-700 flex gap-1"><x-tabler-settings-2/>Admin Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-700 flex gap-1"><x-tabler-lock/>Login</a>

                                @if (Route::has('register') && setting('global.registration_can_user'))
                                    <a href="{{ route('register') }}" class="text-gray-700">Register</a>
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
        <div>
            @if(!empty(setting('global.footer_textarea')))
            <p>
                {{ setting('global.footer_textarea', '') }}
            </p>    
            @endif
            @if (!empty(setting('global.phone')))
            <p>
                tel: {{ setting('global.phone') }} </br>
                e-mail {{ setting('global.email_address') }}
            </p>
            @endif

        <div class="md:flex gap-4 py-8">
            <div class="copyright flex gap-4">
                {{-- Copyright using setting() --}}
                @if (!empty(setting('global.copyright')))
                <span> © {{ date('Y') }} {{ setting('global.copyright') }}</span>
                @endif


            </div>

        <nav><livewire:menu name="footer" /></nav>

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