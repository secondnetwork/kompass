<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between">
            <span>
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    {{-- <span class="">
                        {!! __('pagination.previous') !!}
                    </span> --}}
                @else
                    <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" class="border-gray-300">
                        {!! __('pagination.previous') !!}
                    </button>
                @endif
            </span>

            <span>
                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <button wire:click="nextPage" wire:loading.attr="disabled" rel="next" class="border-gray-300">
                        {!! __('pagination.next') !!}
                    </button>
                @else
                    {{-- <span class="bg-gray-300">
                        {!! __('pagination.next') !!}
                    </span> --}}
                @endif
            </span>
        </nav>
    @endif
</div>

 