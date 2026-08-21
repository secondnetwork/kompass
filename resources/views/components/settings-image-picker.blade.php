@props(['image' => null, 'settingId', 'label' => null, 'class' => ''])

<div class="w-full {{ $class }}">
    @if ($label)
        <label class="text-base-content font-bold text-sm block mb-2">{{ $label }}</label>
    @endif

    <div class="max-w-120">
        @if (! empty($image))
            <div class="relative border border-dashed border-base-300 rounded-lg">
                <div class="absolute inset-0 h-full rounded-lg border text-black/10 dark:text-white/12.5 bg-size-[8px_8px] bg-top-left bg-[repeating-linear-gradient(315deg,currentColor_0,currentColor_1px,transparent_0,transparent_50%)]"></div>
                <img src="{{ url($image) }}?{{ uniqid() }}" class="h-auto relative rounded-lg aspect-video w-full object-contain" />
                <div class="flex absolute top-0 right-0 gap-1 mt-2 mr-2">
                    <button type="button" wire:click="selectItem({{ $settingId }}, 'addMedia')"
                        class="flex items-center px-3 py-1.5 text-xs font-medium text-white rounded-md bg-base-content/70 hover:bg-base-content/90">
                        <x-tabler-edit class="mr-1 w-4 h-4" />
                        <span>{{ __('Change') }}</span>
                    </button>
                    <button type="button" wire:click="removemedia({{ $settingId }})"
                        class="flex items-center px-3 py-1.5 text-xs font-medium text-white rounded-md bg-red-500/70 hover:bg-red-500/90">
                        <x-tabler-trash class="mr-1 w-4 h-4" />
                        <span>{{ __('Remove Image') }}</span>
                    </button>
                </div>
            </div>
        @else
            <button type="button" wire:click="selectItem({{ $settingId }}, 'addMedia')"
                class="relative flex justify-center items-center w-full cursor-pointer rounded-lg aspect-video border border-dashed bg-base-200 border-base-content/20 hover:border-primary px-6 py-10 transition-colors duration-200">
                <div class="text-center">
                    <x-tabler-photo-plus stroke-width="1.5" class="mx-auto size-12 text-gray-400" />
                    <p class="mt-4 text-sm leading-6 text-gray-600">{{ __('Select from media library') }}</p>
                </div>
            </button>
        @endif
    </div>

    <div x-cloak x-data="{ open: @entangle('FormMedia') }">
        <x-kompass::offcanvas :w="'w-3/4'">
            <x-slot name="body">
                @livewire('medialibrary', ['fieldId' => $settingId], 'medialibrary-setting-'.$settingId)
            </x-slot>
        </x-kompass::offcanvas>
    </div>
</div>
