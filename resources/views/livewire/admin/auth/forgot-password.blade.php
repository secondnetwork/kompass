 <div class="flex flex-col gap-6">

    <h2>{{ __('Forgot your password?') }}</h2>
    <div>
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

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
    
    <form class="grid gap-y-8" wire:submit="sendPasswordResetLink">
        @csrf
        

        <x-kompass::form.input wire:model="email" label="{{ __('Email') }}" type="email" value="{{ old('email') }}" name="email" required autocomplete="name" />
    

        <div class="flex">
            <button class="btn btn-primary w-full h-16" type="submit">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>
    </form>
</div>
