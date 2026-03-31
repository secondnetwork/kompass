<?php

namespace Secondnetwork\Kompass\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MenuProfile extends Component
{
    protected $listeners = ['profile-updated' => 'refresh'];

    public function render()
    {
        return view('kompass::components.menu-profile');
    }
}
