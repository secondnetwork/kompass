<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 Server Error - {{ config('app.name') }}</title>

    {{ Vite::useBuildDirectory('content')->withEntryPoints(['resources/css/main.css', 'resources/js/main.js']) }}

</head>
<body class="antialiased font-sans bg-base-100">
    <div class="hero min-h-screen">
        <div class="hero-content flex-col lg:flex-row-reverse items-center">
            {{-- Placeholder for illustration. Get an illustration from https://undraw.co/search and paste the SVG code here --}}
            <svg class="w-1/2" viewBox="0 0 559 427" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M558.299 426.234H0.52832V0.985107H558.299V426.234Z" fill="#E6E6E6"/>
            </svg>
            <div class="max-w-md text-center lg:text-left">
                <h1 class="text-5xl font-bold text-error">500</h1>
                <p class="py-6 text-2xl font-semibold">Oops! Server Error.</p>
                <p class="text-lg text-base-content/70">We are sorry, but something went wrong on our end. Please try again later.</p>
                <div class="mt-8">
                    <a href="{{ url('/') }}" class="btn btn-primary">Go to Homepage</a>
                    <button onclick="history.back()" class="btn btn-ghost ml-4">Go Back</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
