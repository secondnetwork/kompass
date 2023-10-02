@extends('kompass::admin.layouts.guest')

@section('content')

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
    {{-- <h2>{{ __('Forgot your password?') }}</h2> --}}
    <form class="grid gap-y-8" method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <input type="hidden" name="user_id" value="{{ $user->id }}">

        <x-kompass::form.input label="{{ __('Password') }}" type="password" name="password" required autocomplete="new-password" />

        <x-kompass::form.input label="{{ __('Confirm Password') }}" type="password" name="password_confirmation" required autocomplete="new-password" />

        <div class="flex">
            <button class="btn w-full h-16" type="submit">
                {{ __('Create Password') }}  
            </button>
        </div>
    </form>

@endsection
