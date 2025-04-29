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
    <h2>{{ __('Forgot your password?') }}</h2>
    <form class="grid gap-y-8" wire:submit="resetPassword">

        <input wire:model="email" type="hidden" name="email"  >

        <x-kompass::form.input wire:model="password" label="{{ __('Password') }}" type="password" name="password" required autocomplete="new-password" />

        <x-kompass::form.input wire:model="password_confirmation" label="{{ __('Confirm Password') }}" type="password" name="password_confirmation" required autocomplete="new-password" />

        <div class="flex">
            <button class="btn btn-primary w-full h-16" type="submit">
                {{ __('Reset Password') }}  
            </button>
        </div>
    </form>

</div>