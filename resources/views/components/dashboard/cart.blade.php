@props([
    'title' => '',
    'value' => 0,
    'chartId' => null,
    'trend' => null,
    'trendUp' => true,
    'footerLabel' => null,
    'footerValue' => null,
    'link' => null,
    'icon' => null,
    'color' => 'primary',
])

@php
    // Literal class strings (so Tailwind's content scanner picks them up).
    $accent = [
        'primary' => ['bg' => 'bg-primary/10', 'text' => 'text-primary'],
        'success' => ['bg' => 'bg-success/10', 'text' => 'text-success'],
        'warning' => ['bg' => 'bg-warning/10', 'text' => 'text-warning'],
        'info' => ['bg' => 'bg-info/10', 'text' => 'text-info'],
    ][$color] ?? ['bg' => 'bg-primary/10', 'text' => 'text-primary'];
@endphp

<div {{ $attributes->merge(['class' => 'group flex flex-col border border-base-200 shadow-sm hover:shadow-md transition-shadow rounded-xl bg-base-100 overflow-hidden']) }}>
    <div class="p-4 md:p-5 pb-0">
        <div class="flex justify-between items-start gap-3">
            <div class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 truncate">
                    {{ $title }}
                </p>
                <h3 class="mt-1 text-xl sm:text-2xl font-semibold text-base-content">
                    {{ $value }}
                </h3>
            </div>

            <div class="flex flex-col items-end gap-2 shrink-0">
                @if ($icon)
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg {{ $accent['bg'] }} {{ $accent['text'] }}">
                        <x-dynamic-component :component="$icon" class="w-5 h-5" />
                    </span>
                @endif
                @if ($trend !== null)
                    <span class="flex items-center gap-x-1 text-sm font-medium {{ $trendUp ? 'text-success' : 'text-error' }}">
                        @if ($trendUp)
                            <x-tabler-trending-up class="w-4 h-4" />
                        @else
                            <x-tabler-trending-down class="w-4 h-4" />
                        @endif
                        {{ abs($trend) }}%
                    </span>
                @endif
            </div>
        </div>

        {{ $slot }}
    </div>

    @if ($footerLabel || $link)
        <div class="mt-auto border-t border-base-200 p-4 pt-2">
            <div class="flex justify-between items-center">
                @if ($footerLabel)
                    <div class="text-sm font-medium text-gray-400">
                        {{ $footerLabel }}: {{ $footerValue }}
                    </div>
                @endif
                @if ($link)
                    <a href="{{ $link }}" wire:navigate
                        class="inline-flex items-center {{ $accent['text'] }} hover:underline font-medium text-sm ml-auto">
                        {{ __('View') }}
                        <x-tabler-arrow-right class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-0.5" />
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>
