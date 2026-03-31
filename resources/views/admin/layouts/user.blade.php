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
    <meta name="theme-color" content="#ffa700" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#CF8700" media="(prefers-color-scheme: dark)"> 

    <title>{{ config('app.name') }} | Kompass</title>

    @livewireStyles
    @kompassCss

</head>
<body class="kompass-profile">
    <page-main>
    <main class="min-h-screen bg-base-200">
        <header class="navbar bg-base-100 shadow-sm">
            <div class="flex-1">
                <a href="/" class="btn btn-ghost text-xl">
                    @if (!empty(setting('global.adminlogo')))
                        <img src="{{ setting('global.adminlogo') }}" alt="" class="h-8">
                    @else
                        <img src="{{ kompass_asset('kompass_logo.svg') }}" alt="" class="h-8">
                    @endif
                </a>
            </div>
            <div class="flex-none">
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                        <x-kompass::elements.avatar :user="auth()->user()" size="w-10" />
                    </div>
                    <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-50 mt-3 w-52 p-2 shadow">
                        <li><a href="{{ route('profile.dashboard') }}">{{ __('My Profile') }}</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="container mx-auto p-4">
            {{ $slot }}
        </main>
    </main>
    </page-main>

  @livewireScripts
  @kompassJs
  @stack('scripts')

</body>
</html>
