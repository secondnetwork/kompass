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

    /* Mobile menu slide-in animation */
    @keyframes slide-in-left {
        from {
            transform: translateX(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .mobile-menu-slide {
        animation: slide-in-left 0.3s ease-out forwards;
    }

    .mobile-menu-backdrop {
        animation: fade-in 0.2s ease-out forwards;
    }
</style>

</head>

<body class="{{ str_replace('.', '-', Route::currentRouteName()) }}">
    <!-- Global Background Elements -->
    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none bg-brand-dark">
        <!-- Top Left Vibrant Glow -->
        <div class="absolute top-[-20%] left-[-10%] w-[70%] h-[70%] bg-brand-primary/20 rounded-full blur-[150px] animate-pulse"></div>
        <!-- Center Purple Splash -->
        <div class="absolute top-[20%] right-[-10%] w-[60%] h-[60%] bg-purple-500/15 rounded-full blur-[130px]"></div>
        <!-- Bottom Mint Accent -->
        <div class="absolute bottom-[-10%] left-[10%] w-[50%] h-[50%] bg-mint-500/10 rounded-full blur-[120px]"></div>
        <!-- Extra Accent for Depth -->
        <div class="absolute top-[50%] left-[50%] -translate-x-1/2 -translate-y-1/2 w-[80%] h-[40%] bg-red-700/5 rounded-full blur-[160px] rotate-45"></div>
        
        <!-- Grain Texture Overlay -->
        <div class="absolute inset-0 opacity-[0.05] mix-blend-overlay bg-[url('https://www.transparenttextures.com/patterns/stardust.png')]"></div>
    </div>
<!-- ========== HEADER ========== -->
<header class="navbar relative" x-data="{ mobileMenuOpen: false }" @keydown.escape="mobileMenuOpen = false">
  <nav class="relative w-full mx-auto md:flex md:items-center md:justify-between md:gap-3">
    <div class="flex justify-between items-center gap-x-1">
      {{-- <a class="flex-none font-semibold text-xl text-black focus:outline-hidden focus:opacity-80" href="#" aria-label="Brand">Brand</a> --}}
                <x-kompass::elements.logo
                    :height="setting('global.logo_height', '2')"
                    :isImage="(setting('global.logo_type', 'text') == 'image')"
                    :imageSrc="setting('global.logo_image_src', '')"
                    :svgString="setting('global.logo_svg_string', '')"
                />

      <!-- Collapse Button -->
      <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" :aria-expanded="mobileMenuOpen" class="md:hidden relative size-12 flex justify-center items-center font-medium focus:outline-hidden " aria-controls="hs-header-base" aria-label="Toggle navigation">
        <svg x-show="!mobileMenuOpen" class="size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/></svg>
        <svg x-show="mobileMenuOpen" class="size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        <span class="sr-only">Toggle navigation</span>
      </button>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="hs-header-base" 
         x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-x-full opacity-0"
         x-transition:enter-end="translate-x-0 opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0 opacity-100"
         x-transition:leave-end="-translate-x-full opacity-0"
         class="fixed inset-y-0 left-0 z-50 w-full max-w-sm bg-white dark:bg-gray-900 shadow-2xl md:hidden overflow-y-auto"
         x-cloak>
       
      <div class="flex flex-col h-full">
        <!-- Header with close button -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
      
          <button @click="mobileMenuOpen = false" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            <svg class="size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
          </button>
        </div>
        
        <!-- Menu items -->
        <div class="flex-1 p-8">
          <livewire:menus name="main" />
        </div>
        
        <!-- Auth buttons -->
        {{-- <div class="p-4 border-t border-gray-200 dark:border-gray-700 space-y-2">
          @if (Route::has('login'))
              @auth
                  <a href="{{ url('/admin/dashboard') }}" class="btn btn-primary w-full justify-center"><x-tabler-settings-2/>Dashboard</a>
              @else
                  <a href="{{ route('login') }}" class="btn btn-primary w-full justify-center"><x-tabler-lock/>Login</a>
                  @if (Route::has('register') && setting('global.registration_can_user'))
                      <a href="{{ route('register') }}" class="btn btn-outline w-full justify-center">Register</a>
                  @endif
              @endauth
          @endif
        </div> --}}
      </div>
    </div>

    <!-- Desktop Menu (right-aligned) -->
    <div class="hidden md:flex md:items-center md:gap-2 ml-auto">
      <livewire:menus name="main" />

      {{-- <div class="flex items-center gap-2 pl-4 border-l border-gray-200 dark:border-gray-700">
        <button @click="darkMode = !darkMode" class="btn btn-ghost btn-circle btn-sm">
            <x-tabler-sun x-show="darkMode" class="size-5" />
            <x-tabler-moon x-show="!darkMode" class="size-5" />
        </button>

        @if (Route::has('login'))
            @auth
                <a href="{{ url('/admin/dashboard') }}" class="btn btn-primary btn-sm"><x-tabler-settings-2/>Admin</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm"><x-tabler-lock/>Login</a>
                @if (Route::has('register') && setting('global.registration_can_user'))
                    <a href="{{ route('register') }}" class="btn btn-ghost btn-sm">Register</a>
                @endif
            @endauth
        @endif
      </div> --}}
    </div>
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
        <div class="py-16">


              
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <a href="index.html" class="flex items-center mb-4">
                           <x-kompass::elements.logo
                    :height="setting('global.logo_height', '2')" {{-- Default height if not set --}}
                    :isImage="(setting('global.logo_type', 'text') == 'image')" {{-- Default type 'text' if not set --}}
                    :imageSrc="setting('global.logo_image_src', '')" {{-- Default empty string --}}
                    :svgString="setting('global.logo_svg_string', '')" {{-- Default empty string --}}
                />
                    </a>
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
                </div>
         
                    <livewire:menus name="footer" />
             

            </div>
            <div class="border-t border-y-violet-800 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-200">
                @if (!empty(setting('global.copyright')))
                <div> © {{ date('Y') }} {{ setting('global.copyright') }}</div>
                @endif
            </div>
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