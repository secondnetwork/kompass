<div>
  @if (setting('global.registration_can_user'))
  <h3>{{ __('Register') }}</h3>

  @if ($errors->any())
      <div>
          <div>{{ __('Whoops! Something went wrong.') }}</div>

          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif

  <form class="grid gap-y-4" wire:submit="register" :status="session('status')">
      @csrf

      <x-kompass::form.input wire:model="name" label="{{ __('Name') }}" type="text" value="{{ old('Name') }}" name="name" required autofocus autocomplete="name" />

      <x-kompass::form.input wire:model="email" label="{{ __('Email') }}" type="email" value="{{ old('email') }}" name="email" required autocomplete="name" />

      <x-kompass::form.input wire:model="password" label="{{ __('Password') }}" type="password" name="password" required autocomplete="new-password" />

      <x-kompass::form.input wire:model="password_confirmation" label="{{ __('Confirm Password') }}" type="password" value="{{ old('Name') }}" name="password_confirmation" required autocomplete="new-password" />

      
      <a class="text-center text-sm text-base-content/70" href="{{ route('login') }}">
          {{ __('Already registered?') }}
      </a>

      <div>
          <button class="btn btn-primary w-full h-16" type="submit">
              {{ __('Register') }}
          </button>
      </div>
  </form>
  @else
  <h3>{{ __('Service Unavailable') }}</h3>
  @endif

</div>