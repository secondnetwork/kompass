<div class="grid gap-6">
    <div class="max-w-2xl">
        <x-kompass::form.switch wire:model.live="multilingual" label="{{ __('Enable Multilingual Support') }}" />
        <p class="text-sm text-base-content/60 mt-1">
            {{ __('When enabled, you can manage content in multiple languages and use language prefixes in URLs.') }}
        </p>
    </div>

    @if($multilingual)
    <hr class="h-px w-full border-none bg-base-300">

    <div class="max-w-2xl">
        <h4 class="font-bold mb-4">{{ __('Available Languages') }}</h4>
        
        <div class="flex flex-wrap gap-2 mb-6">
            @foreach($available_locales as $locale)
            <div class="inline-flex items-center gap-2 bg-base-200 px-3 py-1.5 rounded-lg border border-base-300">
                <span class="font-bold uppercase text-sm">{{ $locale }}</span>
                <button wire:click="removeLocale('{{ $locale }}')" class="text-error hover:text-error-focus">
                    <x-tabler-x class="w-4 h-4" />
                </button>
            </div>
            @endforeach
        </div>

        <div class="flex gap-4 items-end max-w-sm">
            <div class="flex-1">
                <x-kompass::select wire:model="new_locale" label="{{ __('Add Language') }}" :options="$all_locales" placeholder="{{ __('Select language...') }}" />
            </div>
            <button wire:click="addLocale" class="btn btn-primary h-10 mb-0.5">
                <x-tabler-plus class="w-5 h-5" /> {{ __('Add') }}
            </button>
        </div>
        @error('new_locale') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
        <p class="text-xs text-base-content/50 mt-2 italic">
            {{ __('Use 2-letter ISO codes (de, en, tr, fr, etc.)') }}
        </p>
    </div>
    @endif
</div>
