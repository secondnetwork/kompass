<div class="flex flex-col gap-6">

  @if (session('status'))
  <div>
      {{ session('status') }}
  </div>
@endif

@if ($errors->any())
        <div role="alert" class="alert alert-error alert-soft">
            <span>{{ __('An error has occurred. Please check your inputs.') }}</span>

        </div>
@endif

@if (setting('global.password_login_enabled') !== null ? (bool) setting('global.password_login_enabled') : config('kompass.auth.password_login_enabled', false))

<form wire:submit="login" class="grid gap-y-6">

   <x-kompass::form.input wire:model="email" label="{{ __('E-Mail Address') }}" type="email" value="{{ old('email') }}" name="email" required autocomplete="on" />

  <div>
      <div class="flex justify-between">
      <label class="text-base block mb-1">{{ __('Password') }}</label>
      @if (Route::has('password.request'))
          <a tabindex="-1" class="text-gray-400 hover:text-blue-500 text-base" href="{{ route('password.request') }}">
              {{ __('Forgot your password?') }}
          </a>
      @endif
      </div>
      <x-kompass::form.input wire:model="password" name="password" type="password" required autocomplete="current-password" />
  </div>

  <div class="flex justify-end gap-4 flex-col">
      <button class="btn btn-primary w-full h-14" type="submit" variant="primary">
          {{ __('Login') }}
      </button>

  </div>


  @if (setting('global.registration_can_user'))
      @if (Route::has('register'))
      <a class="text-center text-sm text-base-content/70 hover:text-blue-500" href="{{ route('register') }}">
          {{ __('Don`t have an account? Create One') }}
      </a>
      @endif
  @endif

</form>

<div class="divider text-sm text-base-content/50">{{ __('or') }}</div>

@endif {{-- password_login_enabled --}}


  @if (setting('global.sso') && setting('global.sso-url'))
  @php
      $ssoIcon = setting('global.sso-icon', 'tabler-shield-lock');
      $ssoLabel = setting('global.sso-label') ?: __('Single Sign-On');
  @endphp

  <div class="flex justify-end">
      <a href="/saml2/{{ setting('global.sso-url') }}/login"
          class="btn flex justify-center items-center gap-2 w-full h-14 border-1 border-gray-300 hover:border-blue-500 hover:bg-base-100 px-4 py-2 bg-base-100 text-gray-900">
          @if ($ssoIcon)
              @svg($ssoIcon, 'h-6 w-6')
          @endif
          {{ $ssoLabel }}
      </a>
  </div>
  @endif

{{-- Passkey Login --}}
<div x-data="passkey_authenticate()">
    <template x-if="error">
        <div role="alert" class="alert alert-error alert-soft mb-4">
            <span x-text="error"></span>
        </div>
    </template>
    <button
        type="button"
        class="btn btn-outline w-full h-14 border-gray-300 hover:border-blue-500"
        :disabled="loading"
        @click="authenticate()"
    >
        <span x-show="loading" class="loading loading-spinner loading-sm"></span>
        <x-tabler-fingerprint x-show="!loading" class="w-5 h-5" />
        {{ __('Sign in with Passkey') }}
    </button>
</div>

</div>
