<div class="w-full max-w-lg">

    <div class="w-full pb-4">
        <span class="text-xl font-semibold">{{ __('Passkeys') }}</span>
        <p class="text-base-content/70">{{ __('Sign in without a password using your device biometrics or PIN.') }}</p>
    </div>

    {{-- Registered passkeys --}}
    @if ($passkeys->isNotEmpty())
        <div class="flex flex-col gap-2 mb-6">
            @foreach ($passkeys as $passkey)
                <div class="flex items-center justify-between rounded-lg border border-base-300 px-4 py-3">
                    <div class="flex items-center gap-3">
                        <x-tabler-fingerprint class="w-5 h-5 text-primary shrink-0" />
                        <div>
                            <p class="font-medium text-sm">{{ $passkey->name }}</p>
                            <p class="text-xs text-base-content/50">
                                @if ($passkey->last_used_at)
                                    {{ __('Last used') }} {{ $passkey->last_used_at->diffForHumans() }}
                                @else
                                    {{ __('Never used') }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <button
                        type="button"
                        class="btn btn-ghost btn-sm text-error"
                        wire:click="deletePasskey({{ $passkey->id }})"
                        wire:confirm="{{ __('Remove this passkey?') }}"
                    >
                        <x-tabler-trash class="w-4 h-4" />
                    </button>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-base-content/50 mb-6">{{ __('No passkeys registered yet.') }}</p>
    @endif

    {{-- Add new passkey --}}
    <div
        x-data="passkey_register()"
        @passkey-registered.window="$wire.refresh(); success = false"
    >
        <template x-if="error">
            <div role="alert" class="alert alert-error alert-soft mb-3">
                <span x-text="error"></span>
            </div>
        </template>

        <div class="flex gap-2 items-end">
            <div class="flex-1">
                <label class="text-sm block mb-1">{{ __('Passkey Name') }}</label>
                <input
                    x-model="name"
                    type="text"
                    class="input input-bordered w-full"
                    placeholder="{{ __('e.g. MacBook Pro, iPhone') }}"
                    :disabled="loading"
                />
            </div>
            <button
                type="button"
                class="btn btn-primary"
                :class="{ 'loading': loading }"
                :disabled="loading"
                @click="register()"
            >
                <x-tabler-plus class="w-4 h-4" />
                {{ __('Add') }}
            </button>
        </div>
    </div>

</div>
