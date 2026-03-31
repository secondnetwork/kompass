@props([
    'user' => null,
    'size' => 'w-12',
    'src' => null,
    'label' => null,
    'clickable' => false,
])

@php
    $initials = $user ? nameWithLastInitial($user->name) : ($label ?: null);
    $photoSrc = $src ?? ($user?->profile_photo_path ? $user->profile_photo_url : null);
    $alt = $user?->name ?? 'Avatar';
@endphp

<div class="avatar {{ $clickable ? 'avatar-pointer' : '' }} {{ !$photoSrc ? 'avatar-placeholder' : '' }}">
    <div class="rounded-full relative overflow-hidden {{ !$photoSrc ? 'bg-[#FFA700] text-[#36424A]' : '' }} {{ match($size) {
        'w-6' => 'w-6 h-6 text-[10px]',
        'w-8' => 'w-8 h-8 text-xs',
        'w-10' => 'w-10 h-10 text-sm',
        'w-12' => 'w-12 h-12 text-base',
        'w-16' => 'w-16 h-16 text-lg',
        'w-20' => 'w-20 h-20 text-xl',
        'w-24' => 'w-24 h-24 text-3xl',
        default => 'w-12 h-12 text-base',
    } }}">
        @if ($photoSrc)
            <img alt="{{ $alt }}" src="{{ $photoSrc }}" class="absolute inset-0 w-full h-full object-cover" />
        @elseif ($initials)
            <span class="font-medium">{{ $initials }}</span>
        @else
            <x-tabler-user class="w-1/2 h-1/2" />
        @endif
    </div>

    @if ($clickable)
        <input type="file" class="hidden" x-ref="photo" {{ $attributes->whereStartsWith('wire:model') }} accept="image/*" />
    @endif
</div>
