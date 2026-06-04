@props(['title' => '', 'action' => null])

<div {{ $attributes->merge(['class' => 'py-4 border-t border-base-300 first:border-t-0 first:pt-0']) }}>
    @if ($title)
        <div class="flex items-center justify-between mb-3">
            <h5 class="text-[11px] font-semibold uppercase tracking-wider text-base-content/60">{{ $title }}</h5>
            @isset($action){{ $action }}@endisset
        </div>
    @endif
    <div class="flex flex-col gap-3">{{ $slot }}</div>
</div>
