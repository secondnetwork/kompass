<?php

namespace Secondnetwork\Kompass\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class MenuProfile extends Component
{
    #[On('profile-updated')]
    public function refresh(): void
    {
    }

    public function render()
    {
        return view('kompass::components.menu-profile');
    }
}
