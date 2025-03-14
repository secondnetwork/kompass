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

    <title>{{ config('app.name') }} | Kompass</title>
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

    @if(isset($seo->description))
        <meta name="description" content="{{ $seo->description }}">
    @endif
    @livewireStyles
    @kompassCss

</head>
<body class="kompass-{{ str_replace(".","-", Route::currentRouteName()) }}">
         @env('local') 
         <div style="background-image: linear-gradient(45deg, #fed7aa 25%, #ea580c 25%, #ea580c 50%, #fed7aa 50%, #fed7aa 75%, #ea580c 75%, #ea580c 100%); background-size: 56.57px 56.57px;" class="flex items-center gap-1 bg-orange-600 text-orange-800 h-1 w-full text-center text-xs fixed z-50">
        </div> 
        @endenv
<page-main>
<main class="transition delay-150 duration-300 ease-in-out"  x-data :class="$store.showside.on && 'sideclose'">
    <header class="header">
      
        <div class="header__search flex items-center gap-1">

          <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
        </button>
        </div>

        <x-kompass::menu-profile />

      </header>

      <aside class="sidenav">
        <div class="flex flex-col h-screen">
        <div class="logo">
          @if (!empty(setting('global.admin-logo')))
          <img src="{{ setting('global.admin-logo') }}" alt="">
          @else
        <img class="big-logo" src="{{ kompass_asset('kompass_logo.svg') }}" alt="">
        <img class="favicon-logo" src="{{ kompass_asset('favicon/512.png') }}" alt="">
        @endif
        </div>
        <ul class="sidenav__list">

          <li class="sidenav__list-item"><a wire:navigate @if(Route::is('admin.dashboard')) class="active" @endif href="/admin/dashboard"><x-tabler-chalkboard class="icon-lg"/><span >Dashboard</span></a></li>

          <li class="sidenav__list-item"><a wire:navigate @if(Route::is('admin.posts*')) class="active" @endif href="/admin/posts"><x-tabler-news class="icon-lg"/><span>{{ __('Posts') }}</span></a></li>
          <li class="sidenav__list-item "><a wire:navigate @if(Route::is('admin.pages*')) class="active" @endif href="/admin/pages"><x-tabler-file-text class="icon-lg"/><span>{{ __('Pages') }}</span></a></li>

          <li class="sidenav__list-item"><a wire:navigate @if(Route::is('admin.medialibrary')) class="active" @endif href="/admin/medialibrary"><x-tabler-photo-video class="icon-lg"/><span>{{ __('Media library') }}</span></a></li>
          <livewire:adminmenu name="admin-sidebar-top">
         
          <div class="uppercase text-xs mt-5 px-6 text-gray-500 font-semibold">{{ __('Theme') }}</div>

          <li class="sidenav__list-item"><a wire:navigate @if(Route::is('admin.blocks*')) class="active" @endif href="/admin/blocks"><x-tabler-layout-grid-add class="icon-lg"/><span>{{ __('Block') }}</span></a></li>
          <li class="sidenav__list-item"><a wire:navigate @if(Route::is('admin.menus*')  ) class="active" @endif href="/admin/menus"><x-tabler-layout-navbar class="icon-lg"/><span>{{ __('Menu') }}</span></a></li>
          <li class="sidenav__list-item"><a wire:navigate @if(Route::is('admin.settings*')  ) class="active" @endif href="/admin/settings"><x-tabler-settings class="icon-lg"/><span>{{ __('Settings') }}</span></a></li>
          
          @role(['super_admin','admin'])
          <div class="uppercase text-xs mt-5 px-6 text-gray-500 font-semibold">{{ __('User management') }}</div>
          <li class="sidenav__list-item"><a wire:navigate @if(Route::is('admin.account*')  ) class="active" @endif href="/admin/account"><x-tabler-users class="icon-lg"/><span>{{ __('User account') }}</span></a></li>
          <li class="sidenav__list-item"><a wire:navigate @if(Route::is('admin.roles*')  ) class="active" @endif href="/admin/roles"><x-tabler-lock-access class="icon-lg"/><span>{{ __('Manage Role') }}</span></a></li>
          @endrole
          
          <li class="sidenav__list-item mt-8"><a wire:navigate @if(Route::is('admin.about*')  ) class="active" @endif href="/admin/about"><x-tabler-signature class="icon-lg"/><span>{{ __('About') }}</span></a></li>
        </ul>



        <div class="mt-auto mb-4 mx-6">
          <button x-data @click="$store.showside.toggle()">
            <x-tabler-layout-sidebar-left-collapse class="text-gray-400 sidebarbutton  transition delay-150 duration-300 ease-in-out"/>
          </button>
        </div>
        </div>
      </aside>



      <section class="main-content" wire:transition x-transition>
        @isset($slot)
          {{ $slot }}
        @else
          @yield('content')
        @endisset
      </section>

      <footer>
        <div class="text-xs flex items-center">
          <x-tabler-copyright class="w-4" />{{ \Carbon\Carbon::now()->format('Y') }}  @if (!empty(setting('admin.copyright'))){{ setting('admin.copyright') }} @else secondnetwork @endif| Made with <x-tabler-heart class="w-4 mx-1 stroke-rose-500 fill-rose-500" /> in Hannover, Germany
        </div>
        <div class="text-xs">

          <strong>Laravel</strong> v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
          @php $version = Kompass::getVersion(); @endphp
          @if (!empty($version))
         | <strong>Kompass</strong> {{ $version }}
          @endif
        </div>
      </footer>
</main>
</page-main>

  @livewireScripts
  @kompassJs
  @stack('scripts')

</body>
</html>