<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>429 Too Many Requests - {{ config('app.name') }}</title>

    {{ Vite::useBuildDirectory('content')->withEntryPoints(['resources/css/main.css', 'resources/js/main.js']) }}

</head>
<body class="antialiased font-sans bg-[var(--color-brand-red)] text-[var(--color-text-dark)] min-h-screen flex items-center justify-center overflow-hidden">
    <div class="container-custom py-20 text-center">
        <div class="max-w-4xl mx-auto">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-[var(--color-text-dark)]/10 rounded-full mb-8">
              <span class="w-2 h-2 bg-[var(--color-text-dark)] rounded-full"></span>
              <span class="text-sm font-medium">Error 429</span>
            </div>
            
            <h1 class="text-6xl sm:text-8xl md:text-9xl font-bold mb-6 leading-tight">
                Slow Down!<br>
                <span class="opacity-50">Too Many Requests</span>
            </h1>
            
            <p class="text-xl md:text-2xl text-[var(--color-text-dark)]/80 mb-12 max-w-2xl mx-auto">
                You're moving a bit too fast for us. Please wait a moment and try refreshing the page. We'll be ready when you are.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url()->current() }}" class="btn bg-[var(--color-text-dark)] text-white hover:bg-[var(--color-text-dark-hover)] py-4 px-8 rounded-xl font-bold transition-all">Try Again ↻</a>
                <a href="{{ url('/') }}" class="btn border-2 border-[var(--color-text-dark)] text-[var(--color-text-dark)] hover:bg-[var(--color-text-dark)] hover:text-white py-4 px-8 rounded-xl font-bold transition-all">Go to Homepage</a>
            </div>
        </div>
    </div>
    
    <!-- Background Decoration -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 -z-10 opacity-10 pointer-events-none">
        <span class="text-[20rem] font-bold select-none">429</span>
    </div>
</body>
</html>
