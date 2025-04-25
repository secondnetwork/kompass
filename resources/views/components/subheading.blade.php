@props([
    'size' => null,
])

<div {{ $attributes->class([
    match ($size) {
        'xl' => 'text-lg',
        'lg' => 'text-base',
        default => 'text-sm',
        'sm' => 'text-xs',
    },
    '[:where(&)]:text-gray-500 [:where(&)]:text-base-content/70',
]) }} data-subheading>
    {{ $slot }}
</div>
