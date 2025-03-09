<div class="mt-4 flex flex-col gap-6">
    <p class="text-center">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </p>

    @if (session('status') == 'verification-link-sent')
        <p class="text-center font-medium !dark:text-green-400 !text-green-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </p>
    @endif

    <div class="flex flex-col items-center justify-between space-y-3">
        <button wire:click="sendVerification" variant="primary" class="w-full">
            {{ __('Resend Verification Email') }}
        </button>

        <div class="text-sm cursor-pointer" wire:click="logout">
            {{ __('Logout') }}
        </div>
    </div>

</div>
