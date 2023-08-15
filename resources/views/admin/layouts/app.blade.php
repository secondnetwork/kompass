<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="manifest" href="{{ kompass_asset('favicon/manifest.webmanifest') }} ">
    <link rel="apple-touch-icon" href="{{ kompass_asset('favicon/apple-touch-icon.png') }} ">
    <link rel="icon" href="{{ kompass_asset('favicon/favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ kompass_asset('favicon/icon.svg') }}" type="image/svg+xml">
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


    @kompassCss
</head>
<body class="kompass-{{ str_replace(".","-", Route::currentRouteName()) }}">
<page-main>
<main>
    <header class="header">
        <div class="header__search">
         @if (setting('admin.devmode'))
             <span class="flex items-center gap-1 rounded bg-amber-700 text-amber-400 px-2 py-1 text-sm"><x-tabler-hexagons/>Entwicklungsmodus</span>
         @endif 

        </div>

        <x-kompass::menu-profile />

      </header>

      <aside class="sidenav">
        <div class="sidenav__close-icon">
          <i class="fas fa-times sidenav__brand-close"></i>
        </div>
        <div class="logo">
                @if (!empty(setting('admin.logo')))
            @php
                $file = Secondnetwork\Kompass\Models\File::find(setting('admin.logo'));
            @endphp

            @if ($file)
                @if (Storage::disk('local')->exists('/public/' . $file->path . '/' . $file->slug . '.' . $file->extension))
                    <picture>
                        <source type="image/avif" srcset="{{ asset('storage' . $file->path . '/' .$file->slug)}}.avif ">
                        <img class="w-60" src="{{ asset('storage' . $file->path . '/' . $file->slug . '.' . $file->extension) }}" alt="{{$file->alt}}" />
                    </picture>
                @endif
            @endif
        @else
        <img src="{{ kompass_asset('kompass_logo.svg') }}" alt="">
        @endif
        </div>
        <ul class="sidenav__list">

          <li class="sidenav__list-item"><a wire:navigate @if(Route::is('admin.dashboard')) class="active" @endif href="/admin/dashboard"><x-tabler-chalkboard class="icon-lg"/><span >Dashboard</span></a></li>

          {{-- <li class="sidenav__list-item"><a href="/admin/posts"><x-tabler-news class="icon-lg"/><span>{{ __('Posts') }}</span></a></li> --}}
          <li class="sidenav__list-item "><a wire:navigate @if(Route::is('admin.pages*')) class="active" @endif href="/admin/pages"><x-tabler-file-text class="icon-lg"/><span>{{ __('Pages') }}</span></a></li>

          <livewire:adminmenu name="admin-sidebar-top">
           

          <li class="sidenav__list-item"><a wire:navigate @if(Route::is('admin.medialibrary')) class="active" @endif href="/admin/medialibrary"><x-tabler-photo class="icon-lg"/><span>{{ __('Media library') }}</span></a></li>
          <div class="uppercase text-xs mt-5 px-6 text-gray-500 font-semibold">{{ __('Theme') }}</div>

          <li class="sidenav__list-item"><a wire:navigate @if(Route::is('admin.blocks*')) class="active" @endif href="/admin/blocks"><x-tabler-layout-grid-add class="icon-lg"/><span>{{ __('Block') }}</span></a></li>
          <li class="sidenav__list-item"><a wire:navigate @if(Route::is('admin.menus*')  ) class="active" @endif href="/admin/menus"><x-tabler-layout-navbar class="icon-lg"/><span>{{ __('Menu') }}</span></a></li>
          <li class="sidenav__list-item"><a wire:navigate @if(Route::is('admin.settings*')  ) class="active" @endif href="/admin/settings"><x-tabler-settings class="icon-lg"/><span>{{ __('Settings') }}</span></a></li>


          @canany(['admin','user'])
          <div class="uppercase text-xs mt-5 px-6 text-gray-500 font-semibold">{{ __('Permissions') }}</div>
          <li class="sidenav__list-item"><a wire:navigate @if(Route::is('admin.account*')  ) class="active" @endif href="/admin/account"><x-tabler-users class="icon-lg"/><span>{{ __('User account') }}</span></a></li>
          <li class="sidenav__list-item"><a wire:navigate @if(Route::is('admin.roles*')  ) class="active" @endif href="/admin/roles"><x-tabler-lock-access class="icon-lg"/><span>{{ __('Roles') }}</span></a></li>
          @endcanany

          {{-- @canany(['update', 'view', 'delete'])
              // This user can update, view, or delete
          @elsecanany(['admin', 'user'])
              // This user can create
          @endcanany --}}

          <li class="sidenav__list-item mt-8"><a wire:navigate @if(Route::is('admin.about*')  ) class="active" @endif href="/admin/about"><x-tabler-signature class="icon-lg"/><span>{{ __('About') }}</span></a></li>
        </ul>
      </aside>



      <section class="main-content">
        @isset($slot)
          {{ $slot }}
        @else
          @yield('content')
        @endisset
      </section>

      <footer>
        <div class="text-xs flex items-center">
          <x-tabler-copyright class="w-4" />{{ \Carbon\Carbon::now()->format('Y') }} {{ setting('admin.copyright' ?? 'secondnetwork') }} | Made with <x-tabler-heart class="w-4 mx-1 stroke-rose-500 fill-rose-500" /> in Hannover, Germany
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

  {{-- <wireui:scripts /> --}}
  {{-- @wireUiScripts --}}
  @kompassJs
  @stack('scripts')

</body>
</html>