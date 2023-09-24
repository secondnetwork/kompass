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

<!-- Announcement Banner -->
<div class="bg-gradient-to-r from-purple-600 to-blue-400">
  <div class="max-w-[85rem] px-4 py-0 sm:px-6 lg:px-8 mx-auto">
    <!-- Grid -->
    {{-- <div class="grid justify-center md:grid-cols-2 md:justify-between md:items-center gap-2">
      <div class="text-center md:text-left md:order-2 md:flex md:justify-end md:items-center">

        <a class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-md border-2 border-white font-medium text-white hover:bg-white hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition-all text-sm" href="#">
          Sign up
        </a>
      </div> --}}
      <!-- End Col -->

      <div class="flex items-center">
        <span class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-md font-medium text-white hover:bg-white/[.1] focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition-all text-sm" href="#">
            <x-tabler-test-pipe/>Testsystem
        </span>
        <span class="inline-block border-r border-white/[.3] w-px h-5 mx-2"></span>
        <span class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-md font-medium text-white hover:bg-white/[.1] focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition-all text-sm" href="#">
            <x-tabler-hexagons/>Entwicklungsmodus
        </span>
      </div>
      <!-- End Col -->
    </div>
    <!-- End Grid -->
  </div>
</div>
<!-- End Announcement Banner -->


<header>

    <div class="relative md:flex md:items-center md:justify-between">
      <div class="flex items-center justify-between">
        <a class="flex-none text-xl font-semibold " href="/" aria-label="Brand">

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
        @endif
           </a>
      </div>


          <div class="flex flex-col gap-x-0 mt-5 divide-y divide-dashed divide-gray-200 md:flex-row md:items-center md:justify-end md:gap-x-7 md:mt-0 md:pl-7 md:divide-y-0 md:divide-solid ">

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
  </nav>
</header>





    <main>
        @if ('is_front_page' == str_replace(".","-", Route::currentRouteName()))

        <div>
        <section class="fullpage">
  <!-- Grid -->
  <div class="grid md:grid-cols-2 gap-4 md:gap-8 xl:gap-20 pb-16 md:items-center">
    <div>
      <h1 class="block text-3xl font-bold text-gray-800 sm:text-4xl lg:text-4xl lg:leading-tight ">Beginnen Sie Ihre Reise mit <span class="text-cyan-600 text-4xl">Regionalverband Braunschweig</span></h1>
      <p class="mt-3 text-lg text-gray-800 ">Wir erarbeiten eine Regionalstrategie und sorgen für Orientierung in Zeiten der Energiewende. Wir denken nachhaltig und visionär. Wir organisieren regionsübergreifende Zusammenarbeit. Wir gestalten heute für morgen.</p>

    </div>
    <!-- End Col -->

    <div class="relative">
      <img class="w-full rounded-md" src="https://images.unsplash.com/photo-1584371577738-40b929ee5f13?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=700&h=800&q=80" alt="Image Description">
       <!-- SVG-->
      <div class="absolute bottom-0 left-0">
        <svg class="w-2/3 ml-auto h-auto text-white " width="630" height="451" viewBox="0 0 630 451" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="531" y="352" width="99" height="99" fill="currentColor"/>
          <rect x="140" y="352" width="106" height="99" fill="currentColor"/>
          <rect x="482" y="402" width="64" height="49" fill="currentColor"/>
          <rect x="433" y="402" width="63" height="49" fill="currentColor"/>
          <rect x="384" y="352" width="49" height="50" fill="currentColor"/>
          <rect x="531" y="328" width="50" height="50" fill="currentColor"/>
          <rect x="99" y="303" width="49" height="58" fill="currentColor"/>
          <rect x="99" y="352" width="49" height="50" fill="currentColor"/>
          <rect x="99" y="392" width="49" height="59" fill="currentColor"/>
          <rect x="44" y="402" width="66" height="49" fill="currentColor"/>
          <rect x="234" y="402" width="62" height="49" fill="currentColor"/>
          <rect x="334" y="303" width="50" height="49" fill="currentColor"/>
          <rect x="581" width="49" height="49" fill="currentColor"/>
          <rect x="581" width="49" height="64" fill="currentColor"/>
          <rect x="482" y="123" width="49" height="49" fill="currentColor"/>
          <rect x="507" y="124" width="49" height="24" fill="currentColor"/>
          <rect x="531" y="49" width="99" height="99" fill="currentColor"/>
        </svg>
      </div>
      <!-- End SVG-->
    </div>
    <!-- End Col -->
  </div>
  <!-- End Grid -->
        </section>
        </div>
        @endif
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

    @livewireScripts
    {{ Vite::useBuildDirectory('content')->withEntryPoints(['resources/js/main.js']) }}

</body>

</html>
