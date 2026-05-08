<div class="flex flex-col gap-6">

    <div>
        <h2 class="text-2xl font-semibold">{{ __('Set up a Passkey') }}</h2>
        <p class="text-base-content/60 mt-1 text-sm">
            {{ __('Passkeys let you sign in securely with your fingerprint, face, or device PIN — no password needed.') }}
        </p>
    </div>

    <div
        x-data="passkey_register()"
        @passkey-registered.window="window.location.href = '{{ route('admin.dashboard') }}'"
    >

        <template x-if="error">
            <div role="alert" class="alert alert-error alert-soft mb-4">
                <span x-text="error"></span>
            </div>
        </template>

        <div class="grid gap-y-4">
            <div>
                <label class="text-base block mb-1">{{ __('Passkey Name') }}</label>
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
                class="btn btn-primary w-full h-14"
                :class="{ 'loading': loading }"
                :disabled="loading"
                @click="register()"
            >
                <x-tabler-fingerprint class="w-5 h-5" />
                {{ __('Register Passkey') }}
            </button>
        </div>
    </div>

    @if (!config('kompass.auth.force_passkey_on_first_login', true))
        <a href="{{ route('admin.dashboard') }}" class="text-center text-sm text-base-content/50 hover:text-blue-500">
            {{ __('Skip for now') }}
        </a>
    @endif

</div>
