@extends('kompass::admin.layouts.guest')
@section('title') Kompass @endsection
@section('content')

            @if (session('status'))
                <div>
                    {{ session('status') }}
                </div>
            @endif

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

            <form method="POST" action="{{ route('login') }}" class="grid gap-y-6">
                @csrf

                 <x-kompass::form.input label="{{ __('E-Mail Address') }}" type="email" value="{{ old('email') }}" name="email" required autocomplete="on" />

                <div>
                    <div class="flex justify-between">
                    <label class="text-base block mb-1">{{ __('Password') }}</label>
                    @if (Route::has('password.request'))
                        <a class="text-gray-400 hover:text-blue-500 text-base" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                    </div>
                    <x-kompass::form.input name="password" type="password" required autocomplete="current-password" />
                </div>


                <div class="flex justify-end">
                    <button class="btn bg-primary w-full h-14" type="submit">
                        {{ __('Login') }}
                    </button>
                </div>


                {{-- <div class="text-center text-sm text-gray-500">{{ __('or sign in with') }}</div>
                <div class="flex justify-end">
                    <a href="/saml2/###/login"
                        class="btn flex justify-center items-center w-full h-14 border-1 border-gray-300 hover:border-blue-500 hover:bg-white px-4 py-2 bg-white text-gray-900">

                        <svg class="h-8 w-8 pr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 47 48">
                            <g fill="none" fill-rule="evenodd" stroke="none" stroke-width="1">
                                <g transform="translate(-401.000000, -860.000000)">
                                    <g transform="translate(401.000000, 860.000000)">
                                        <path fill="#FBBC05"
                                            d="M9.82727273,24 C9.82727273,22.4757333 10.0804318,21.0144 10.5322727,19.6437333 L2.62345455,13.6042667 C1.08206818,16.7338667 0.213636364,20.2602667 0.213636364,24 C0.213636364,27.7365333 1.081,31.2608 2.62025,34.3882667 L10.5247955,28.3370667 C10.0772273,26.9728 9.82727273,25.5168 9.82727273,24" />
                                        <path fill="#EB4335"
                                            d="M23.7136364,10.1333333 C27.025,10.1333333 30.0159091,11.3066667 32.3659091,13.2266667 L39.2022727,6.4 C35.0363636,2.77333333 29.6954545,0.533333333 23.7136364,0.533333333 C14.4268636,0.533333333 6.44540909,5.84426667 2.62345455,13.6042667 L10.5322727,19.6437333 C12.3545909,14.112 17.5491591,10.1333333 23.7136364,10.1333333" />
                                        <path fill="#34A853"
                                            d="M23.7136364,37.8666667 C17.5491591,37.8666667 12.3545909,33.888 10.5322727,28.3562667 L2.62345455,34.3946667 C6.44540909,42.1557333 14.4268636,47.4666667 23.7136364,47.4666667 C29.4455,47.4666667 34.9177955,45.4314667 39.0249545,41.6181333 L31.5177727,35.8144 C29.3995682,37.1488 26.7323182,37.8666667 23.7136364,37.8666667" />
                                        <path fill="#4285F4"
                                            d="M46.1454545,24 C46.1454545,22.6133333 45.9318182,21.12 45.6113636,19.7333333 L23.7136364,19.7333333 L23.7136364,28.8 L36.3181818,28.8 C35.6879545,31.8912 33.9724545,34.2677333 31.5177727,35.8144 L39.0249545,41.6181333 C43.3393409,37.6138667 46.1454545,31.6490667 46.1454545,24" />
                                    </g>
                                </g>
                            </g>
                        </svg>
                        Google
                    </a>
                </div> --}}
                @if (Route::has('register'))
                <a class="text-center text-sm text-gray-500 hover:text-blue-500" href="{{ route('register') }}">
                    {{ __('Don`t have an account? Create One') }}
                </a>
                @endif

            </form>




@endsection
