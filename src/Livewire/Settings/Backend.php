<?php

namespace Secondnetwork\Kompass\Livewire\Settings;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class Backend extends Component
{
    public $registration_can_user;

    public $copyright;

    public function mount()
    {
        $this->registration_can_user = config('kompass.settings.registration_can_user');
        $this->copyright = config('kompass.settings.copyright_backend');
    }

    public function updating($property, $value)
    {
        if ($property == 'registration_can_user') {
            $this->updateConfigKeyValue('registration_can_user', $value);
        }
        if ($property == 'copyright') {
            $this->updateConfigKeyValue('copyright_backend', $value);
        }
    }

    public function updateConfigKeyValue($key, $value)
    {
        \Config::write('kompass.settings.'.$key, $value);
        Artisan::call('config:clear');

        // $this->js('savedMessageOpen()');
    }

    public function render()
    {
        return view('kompass::livewire.settings.backend');
    }
}
