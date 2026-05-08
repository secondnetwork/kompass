<?php

namespace Secondnetwork\Kompass\Livewire\Settings;

use Illuminate\Support\Facades\Auth;
use Laravel\Passkeys\Passkey;
use Livewire\Attributes\On;
use Livewire\Component;

class PasskeyManagement extends Component
{
    #[On('passkey-registered')]
    public function refresh(): void {}

    public function deletePasskey(int $passkeyId): void
    {
        $passkey = Passkey::findOrFail($passkeyId);

        abort_if($passkey->user_id !== Auth::id(), 403);

        $passkey->delete();
    }

    public function render()
    {
        return view('kompass::livewire.settings.passkey-management', [
            'passkeys' => Auth::user()->passkeys()->latest()->get(),
        ]);
    }
}
