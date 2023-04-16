<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('kompass::livewire.dashboard', [
            'pages' => $this->resultDate(),
        ])->layout('kompass::admin.layouts.app');
    }
}
