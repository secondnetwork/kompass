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

    <div class="dropdown dropdown-end dropdown-hover">
        <div tabindex="0" role="button" class="btn btn-ghost btn-sm m-1 uppercase flex items-center gap-1">
            <x-tabler-language class="size-4" />
            <span class="hidden sm:inline">{{ $currentLocale }}</span>
            <x-tabler-chevron-down class="size-3 opacity-50" />
        </div>
        <ul tabindex="0" class="dropdown-content z-50 menu p-2 shadow-xl bg-base-100 border border-base-300 rounded-box w-32">
            @foreach($availableLocales as $locale)
                @php
                    $targetUrl = ($locale == $defaultLocale) 
                        ? url($slugPath ?: '/') 
                        : url($locale . ($slugPath ? '/' . $slugPath : ''));
                @endphp
                <li>
                    <a href="{{ $targetUrl }}" class="flex justify-between items-center uppercase @if($locale == $currentLocale) active bg-primary text-primary-content @endif">
                        <span>{{ $locale }}</span>
                        @if($locale == $currentLocale)
                            <x-tabler-check class="size-4" />
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
