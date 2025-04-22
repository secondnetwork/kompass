<div>

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

    <form wire:submit="confirmPassword">

        <x-kompass::form.input wire:model="password" label="{{ __('Password') }}" type="password" name="password" required autocomplete="current-password" />

        <div>
            <button class="btn w-full h-16" type="submit">
                {{ __('Confirm Password') }}
            </button>
        </div>

        @if (Route::has('password.request'))
            <a class="text-center text-sm text-base-content/70" href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
        @endif
    </form>
</div>
