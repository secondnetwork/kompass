@if (setting('global.multilingual'))
    @php
        $localesData = setting('global.available_locales');
        if ($localesData) {
            $availableLocales = is_array($localesData) ? $localesData : json_decode($localesData, true);
        } else {
            $availableLocales = ['de', 'en', 'tr'];
        }
        
        $currentLocale = app()->getLocale();
        $defaultLocale = $availableLocales[0] ?? 'de';
        
        $segments = request()->segments();
        
        // Remove locale prefix if exists
        if (count($segments) > 0 && in_array($segments[0], $availableLocales)) {
            array_shift($segments);
        }
        
        $slugPath = implode('/', $segments);
    @endphp

    <div x-data="{ open: false }" class="relative inline-block text-left">
        <button 
            @click="open = !open" 
            type="button" 
            class="inline-flex items-center gap-x-2 px-3 py-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 uppercase"
        >
            <x-tabler-language class="size-4" />
            {{ $currentLocale }}
            <x-tabler-chevron-down class="size-3 transition-transform" ::class="open ? 'rotate-180' : ''" />
        </button>

        <div 
            x-show="open" 
            @click.away="open = false"
            class="absolute right-0 mt-2 w-32 bg-white shadow-xl border border-gray-200 rounded-md overflow-hidden z-[999] dark:bg-slate-800 dark:border-gray-700"
            x-cloak
        >
            <div class="flex flex-col py-1">
                @foreach($availableLocales as $locale)
                    @php
                        $targetUrl = ($locale == $defaultLocale) 
                            ? url($slugPath ?: '/') 
                            : url($locale . ($slugPath ? '/' . $slugPath : ''));
                    @endphp
                    <a 
                        href="{{ $targetUrl }}" 
                        wire:navigate 
                        class="flex justify-between items-center px-4 py-2 text-sm uppercase transition-colors @if($locale == $currentLocale) bg-blue-50 text-blue-600 font-bold dark:bg-slate-700 dark:text-blue-400 @else text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700 @endif"
                    >
                        <span>{{ $locale }}</span>
                        @if($locale == $currentLocale)
                            <x-tabler-check class="size-4" />
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif
