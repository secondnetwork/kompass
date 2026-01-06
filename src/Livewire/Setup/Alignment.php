<?php

namespace Secondnetwork\Kompass\Livewire\Setup;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class Alignment extends Component
{
    public $heading;

    public $container;

    public function mount()
    {
        $this->heading = config('kompass.appearance.alignment.heading');
        $this->container = config('kompass.appearance.alignment.container');

        if (! isset($this->heading)) {
            $this->heading = 'center';
        }

        if (! isset($this->container)) {
            $this->container = 'center';
        }
    }

    public function updatingHeading($value)
    {
        $this->updateConfigKeyValue('alignment.heading', $value);
    }

    public function updatingContainer($value)
    {
        $this->updateConfigKeyValue('alignment.container', $value);
    }

    private function updateConfigKeyValue($key, $value)
    {
        \Config::write('kompass.appearance.'.$key, $value);
        Artisan::call('config:clear');
       // $this->js('savedMessageOpen()');
    }

    public function render()
    {
        return view('kompass::livewire.setup.alignment');
    }
}
