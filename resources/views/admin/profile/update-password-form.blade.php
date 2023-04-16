{{-- <form method="POST" action="{{ route('user-password.update') }}">
    @csrf
    @method('PUT')

    <div>
        <label>{{ __('Current Password') }}</label>
        <input type="password" name="current_password" required autocomplete="current-password" />
    </div>

    <div>
        <label>{{ __('Password') }}</label>
        <input type="password" name="password" required autocomplete="new-password" />
    </div>

    <div>
        <label>{{ __('Confirm Password') }}</label>
        <input type="password" name="password_confirmation" required autocomplete="new-password" />
    </div>

    <div>
        <button type="submit">
            {{ __('Save') }}
        </button>
    </div>
</form> --}}

<x-kompass::form-section submit="updatePassword">
    <x-slot name="title">
        {{ __('Update Password') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6">
            <x-kompass::label for="current_password" value="{{ __('Current Password') }}" />
            <x-kompass::input id="current_password" type="password" class="mt-1 block w-full" wire:model.defer="state.current_password" autocomplete="current-password" />
            <x-kompass::input-error for="current_password" class="mt-2" />
        </div>

        <div class="col-span-6">
            <x-kompass::label for="password" value="{{ __('New Password') }}" />
            <x-kompass::input id="password" type="password" class="mt-1 block w-full" wire:model.defer="state.password" autocomplete="new-password" />
            <x-kompass::input-error for="password" class="mt-2" />
        </div>

        <div class="col-span-6">
            <x-kompass::label for="password_confirmation" value="{{ __('Confirm Password') }}" />
            <x-kompass::input id="password_confirmation" type="password" class="mt-1 block w-full" wire:model.defer="state.password_confirmation" autocomplete="new-password" />
            <x-kompass::input-error for="password_confirmation" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-kompass::action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-kompass::action-message>

        <x-kompass::button>
            {{ __('Save') }}
        </x-kompass::button>
    </x-slot>
</x-kompass::form-section>
