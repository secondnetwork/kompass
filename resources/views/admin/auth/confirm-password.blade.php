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

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <label>{{ __('Password') }}</label>
            <input class="h-16" type="password" name="password" required autocomplete="current-password" />
        </div>

        <div>
            <button class="btn w-full h-16" type="submit">
                {{ __('Confirm Password') }}
            </button>
        </div>

        @if (Route::has('password.request'))
            <a class="text-center text-sm text-gray-500" href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
        @endif
    </form>


@endsection
