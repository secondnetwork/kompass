@extends('kompass::admin.layouts.guest')

@section('content')

        <h2>{{ __('Register') }}</h2>

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

        <div>
            <label>{{ __('Name') }}</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
        </div>

        <div>
            <label>{{ __('Email') }}</label>
            <input type="email" name="email" value="{{ old('email') }}" required />
        </div>

        <div>
            <label>{{ __('Password') }}</label>
            <input type="password" name="password" required autocomplete="new-password" />
        </div>

        <div>
            <label>{{ __('Confirm Password') }}</label>
            <input type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>

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
