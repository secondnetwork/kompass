<?php

namespace Secondnetwork\Kompass\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('kompass::admin.layouts.auth')]
class PasskeySetup extends Component
{
    public function render()
    {
        return view('kompass::livewire.admin.auth.passkey-setup');
    }
}
