@props([
    'paginator' => null,
])

@php
    // Works with a LengthAwarePaginator (knows total + last page),
    // a simple Paginator (only prev/next), or a plain Collection.
    $isPaginator = is_object($paginator) && method_exists($paginator, 'hasPages');
    $isLengthAware = is_object($paginator) && method_exists($paginator, 'lastPage') && method_exists($paginator, 'total');

    $resultCount = $isLengthAware
        ? $paginator->total()
        : ((is_countable($paginator) || is_object($paginator)) ? $paginator->count() : 0);

    // Build a windowed list of page numbers (with "…" gaps) for length-aware paginators.
    $pageLinks = [];
    if ($isLengthAware) {
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $window = 1;
        $prev = 0;
        for ($i = 1; $i <= $last; $i++) {
            if ($i == 1 || $i == $last || ($i >= $current - $window && $i <= $current + $window)) {
                if ($prev && $i - $prev > 1) {
                    $pageLinks[] = '...';
                }
                $pageLinks[] = $i;
                $prev = $i;
            }
        }
    }

    // Numbered page button (ghost style — pops only on hover / when active).
    $pageBtn = 'inline-flex items-center justify-center size-9 text-sm font-medium rounded-lg text-base-content/70 hover:bg-base-200 hover:text-base-content transition';
    $pageActive = 'inline-flex items-center justify-center size-9 text-sm font-semibold rounded-lg bg-primary text-primary-content shadow-sm';
    // Prev / Next — bordered nav controls.
    $navBtn = 'inline-flex items-center justify-center gap-1 h-9 px-2.5 text-sm font-medium rounded-lg border border-base-300 text-base-content hover:bg-base-200 hover:border-base-content/20 transition';
    $navDisabled = 'inline-flex items-center justify-center gap-1 h-9 px-2.5 text-sm font-medium rounded-lg border border-base-300 text-base-content/30 cursor-not-allowed select-none';
@endphp

@if ($paginator !== null)
    <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-3 border-t border-base-300 bg-base-100">
        <div class="text-sm text-base-content/60">
            @if ($isLengthAware && $paginator->hasPages())
                {{ __(':shown of :total results', ['shown' => $paginator->count(), 'total' => $paginator->total()]) }}
            @else
                {{ $resultCount }} {{ __('results') }}
            @endif
        </div>

        @if ($isPaginator && $paginator->hasPages())
            <div class="flex items-center gap-1.5">
                {{-- Prev --}}
                @if ($paginator->onFirstPage())
                    <span class="{{ $navDisabled }}"><x-tabler-chevron-left class="size-4" /><span class="hidden sm:inline">{{ __('Prev') }}</span></span>
                @else
                    <button type="button" wire:click="previousPage" wire:loading.attr="disabled" class="{{ $navBtn }}" aria-label="{{ __('Prev') }}">
                        <x-tabler-chevron-left class="size-4" /><span class="hidden sm:inline">{{ __('Prev') }}</span>
                    </button>
                @endif

                {{-- Numbered pages (length-aware only) --}}
                @if (count($pageLinks))
                    <div class="flex items-center gap-1 mx-1">
                        @foreach ($pageLinks as $link)
                            @if ($link === '...')
                                <span class="inline-flex items-center justify-center size-9 text-sm text-base-content/40 select-none">…</span>
                            @elseif ($link == $paginator->currentPage())
                                <span class="{{ $pageActive }}" aria-current="page">{{ $link }}</span>
                            @else
                                <button type="button" wire:click="gotoPage({{ $link }})" wire:loading.attr="disabled" class="{{ $pageBtn }}">{{ $link }}</button>
                            @endif
                        @endforeach
                    </div>
                @endif

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <button type="button" wire:click="nextPage" wire:loading.attr="disabled" class="{{ $navBtn }}" aria-label="{{ __('Next') }}">
                        <span class="hidden sm:inline">{{ __('Next') }}</span><x-tabler-chevron-right class="size-4" />
                    </button>
                @else
                    <span class="{{ $navDisabled }}"><span class="hidden sm:inline">{{ __('Next') }}</span><x-tabler-chevron-right class="size-4" /></span>
                @endif
            </div>
        @endif
    </div>
@endif
