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
    <h2>{{ __('Forgot your password?') }}</h2>
    <form class="grid gap-y-8" method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
        	<label class="pb-4 block">{{ __('Email') }}</label>
        	<input class="h-16" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus />
        </div>

        <div>
            <label class="pb-4 block">{{ __('Password') }}</label>
            <input class="h-16" type="password" name="password" required autocomplete="new-password" />
        </div>

        <div>
            <label class="pb-4 block">{{ __('Confirm Password') }}</label>
            <input class="h-16" type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>

        <div class="flex">
            <button class="btn w-full h-16" type="submit">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>

@endsection
