<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" x-init="$watch('darkMode', val => { 
    localStorage.setItem('theme', val ? 'dark' : 'blaiq');
    document.documentElement.setAttribute('data-theme', val ? 'dark' : 'blaiq');
})" :data-theme="darkMode ? 'dark' : 'blaiq'">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script>
    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    } else {
        document.documentElement.setAttribute('data-theme', 'blaiq');
    }
</script>
{{-- Assuming x-seo::meta uses settings internally or doesn't need direct changes here --}}
<x-seo::meta />
@if (setting('global.favicon_light_image_path'))
<link href="{{ url(setting('global.favicon_light_image_path')) }}" rel="icon" media="(prefers-color-scheme: light)" />
<link rel="manifest" href="{{ asset('favicon/site.webmanifest') }} ">
<link rel="apple-touch-icon" href="{{ asset('favicon/apple-touch-icon.png') }} ">
@endif
@if (setting('global.favicon_dark_image_path', ''))
<link href="{{ url(setting('global.favicon_dark_image_path')) }}" rel="icon" media="(prefers-color-scheme: dark)" />
@endif
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

<!-- ========== HEADER ========== -->
<header class="navbar">
  <nav class="relative  w-full mx-auto md:flex md:items-center md:justify-between md:gap-3">
    <div class="flex justify-between items-center gap-x-1">
      {{-- <a class="flex-none font-semibold text-xl text-black focus:outline-hidden focus:opacity-80" href="#" aria-label="Brand">Brand</a> --}}
                <x-kompass::elements.logo
                    :height="setting('global.logo_height', '2')" {{-- Default height if not set --}}
                    :isImage="(setting('global.logo_type', 'text') == 'image')" {{-- Default type 'text' if not set --}}
                    :imageSrc="setting('global.logo_image_src', '')" {{-- Default empty string --}}
                    :svgString="setting('global.logo_svg_string', '')" {{-- Default empty string --}}
                />

      <!-- Collapse Button -->
      <button type="button" class="hs-collapse-toggle md:hidden relative size-9 flex justify-center items-center font-medium text-sm rounded-lg border border-gray-200 text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none" id="hs-header-base-collapse"  aria-expanded="false" aria-controls="hs-header-base" aria-label="Toggle navigation"  data-hs-collapse="#hs-header-base" >
        <svg class="hs-collapse-open:hidden size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/></svg>
        <svg class="hs-collapse-open:block shrink-0 hidden size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        <span class="sr-only">Toggle navigation</span>
      </button>
      <!-- End Collapse Button -->
    </div>

    <!-- Collapse -->
    <div id="hs-header-base" class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow md:block "  aria-labelledby="hs-header-base-collapse" >
      <div class="overflow-hidden overflow-y-auto max-h-[75vh] [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300">
        <div class="py-2 md:py-0  flex flex-col md:flex-row md:items-center gap-0.5 md:gap-1">
          <div class="grow">
            <div class="flex flex-col md:flex-row md:justify-end md:items-center gap-0.5 md:gap-1">
            
            <livewire:menus name="main" />

  


            </div>
          </div>

          <div class="my-2 md:my-0 md:mx-2">
            <div class="w-full h-px md:w-px md:h-4 bg-gray-100 md:bg-gray-300"></div>
          </div>

          <div class="flex items-center gap-1">
            <button @click="darkMode = !darkMode" class="btn btn-ghost btn-circle">
                <x-tabler-sun x-show="darkMode" class="size-5" />
                <x-tabler-moon x-show="!darkMode" class="size-5" />
            </button>
          </div>

       <div class="pt-3 md:pt-0">
                    @if (Route::has('login'))
                        <div class=" flex gap-4 ">
                            @auth
                                <a href="{{ url('/admin/dashboard') }}" class="btn btn-primary"><x-tabler-settings-2/>Admin Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class=" btn btn-primary"><x-tabler-lock/>Login</a>

                                @if (Route::has('register') && setting('global.registration_can_user'))
                                    <a href="{{ route('register') }}" class="btn btn-accent">Register</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
        </div>
      </div>
    </div>
    <!-- End Collapse -->
  </nav>
</header>
<!-- ========== END HEADER ========== -->


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
            <div class="">
                      
                <a class="flex-none font-semibold text-xl text-black focus:outline-hidden focus:opacity-80" href="#" aria-label="Brand">Brand</a>
                @if (!empty(setting('global.copyright')))
                <p> © {{ date('Y') }} {{ setting('global.copyright') }}</p>
                @endif
            


            </div>

        <nav><livewire:menus name="footer" /></nav>

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