@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex justify-center my-8">
        <div class="join">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <button type="button" class="join-item btn btn-md disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <x-tabler-chevron-left class="size-5" />
                </button>
            @else
                <button wire:click="previousPage" wire:loading.attr="disabled" type="button" class="join-item btn btn-md" aria-label="@lang('pagination.previous')">
                    <x-tabler-chevron-left class="size-5" />
                </button>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <button class="join-item btn btn-md disabled" aria-disabled="true">{{ $element }}</button>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <button type="button" class="join-item btn btn-md btn-active" aria-current="page">{{ $page }}</button>
                        @else
                            <button wire:click="gotoPage({{ $page }})" wire:loading.attr="disabled" type="button" class="join-item btn btn-md" aria-label="@lang('pagination.goto_page', ['page' => $page])">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled" type="button" class="join-item btn btn-md" aria-label="@lang('pagination.next')">
                    <x-tabler-chevron-right class="size-5" />
                </button>
            @else
                <button type="button" class="join-item btn btn-md disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <x-tabler-chevron-right class="size-5" />
                </button>
            @endif
        </div>
    </nav>
@endif
