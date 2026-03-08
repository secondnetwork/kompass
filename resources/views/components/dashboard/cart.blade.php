@props([
    'title' => '',
    'value' => 0,
    'chartId' => null,
    'trend' => null,
    'trendUp' => true,
    'footerLabel' => null,
    'footerValue' => null,
    'link' => null,
    'color' => 'primary'
])

<div {{ $attributes->merge(['class' => 'flex flex-col border border-base-200 shadow-sm rounded-xl bg-base-100 overflow-hidden']) }}>
    <div class="p-4 md:p-5 pb-0">
        <div class="flex justify-between items-center">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                {{ $title }}
            </p>
            @if($trend !== null)
            <span class="flex items-center gap-x-1 text-sm font-medium {{ $trendUp ? 'text-success' : 'text-error' }}">
                @if($trendUp)
                    <x-tabler-trending-up class="w-4 h-4" />
                @else
                    <x-tabler-trending-down class="w-4 h-4" />
                @endif
                {{ abs($trend) }}%
            </span>
            @endif
        </div>

        <div class="mt-1 flex items-center gap-x-2">
            <h3 class="text-xl sm:text-2xl font-medium text-base-content">
                {{ $value }}
            </h3>
        </div>
        
        {{ $slot }}
    </div>

    {{-- @if($chartId)
    <div class="h-[60px] p-1 mt-auto">
        <div id="{{ $chartId }}"></div>
    </div>
    @endif --}}

    @if($footerLabel || $link)
    <div class="mt-auto border-t border-base-200 p-4 pt-2">
        <div class="flex justify-between items-center">
            @if($footerLabel)
            <div class="text-sm font-medium text-gray-400">
                {{ $footerLabel }}: {{ $footerValue }}
            </div>
            @endif
            @if($link)
            <a href="{{ $link }}" class="inline-flex items-center text-{{ $color }} hover:underline font-medium text-sm ml-auto">
                {{ __('View') }}
                <x-tabler-arrow-right class="w-4 h-4 ml-1" />
            </a>
            @endif
        </div>
    </div>
    @endif
</div>
