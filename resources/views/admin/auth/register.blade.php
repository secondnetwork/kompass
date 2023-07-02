@extends('kompass::admin.layouts.guest')

@section('content')

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

    <form class="grid gap-y-4" method="POST" action="{{ route('register') }}">
        @csrf

        <x-kompass::form.input label="{{ __('Name') }}" type="text" value="{{ old('Name') }}" name="name" required autofocus autocomplete="name" />

        <x-kompass::form.input label="{{ __('Email') }}" type="email" value="{{ old('email') }}" name="email" required autocomplete="name" />

        <x-kompass::form.input label="{{ __('Password') }}" type="password" name="password" required autocomplete="new-password" />

        <x-kompass::form.input label="{{ __('Confirm Password') }}" type="password" value="{{ old('Name') }}" name="password_confirmation" required autocomplete="new-password" />

        
        <a class="text-center text-sm text-gray-500" href="{{ route('login') }}">
            {{ __('Already registered?') }}
        </a>

        <div>
            <button class="btn w-full h-16" type="submit">
                {{ __('Register') }}
            </button>
        </div>
    </form>

@endsection
