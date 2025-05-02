<?php

namespace Secondnetwork\Kompass\Livewire\Settings;

use Livewire\Component;
use Secondnetwork\Kompass\Models\Setting;

class Backend extends Component
{
    public $registration_can_user;
    public $copyright;

    private $dbKeyRegistration = 'registration_can_user';
    private $dbKeyCopyright = 'copyright_backend';

    public function mount()
    {
        $globalSettings = Setting::global()->get()->keyBy('key');

        $this->registration_can_user = (bool) optional($globalSettings->get($this->dbKeyRegistration))->data ?? false;
        $this->copyright = optional($globalSettings->get($this->dbKeyCopyright))->data ?? '';
    }

    public function updating($property, $value)
    {
        if ($property === 'registration_can_user') {
            $this->updateSettingInDatabase($this->dbKeyRegistration, (string) $value);
        }
        if ($property === 'copyright') {
            $this->updateSettingInDatabase($this->dbKeyCopyright, $value);
        }
    }

    private function updateSettingInDatabase($key, $value)
    {
        Setting::updateOrCreate(
            [
                'key' => $key,
                'group' => 'global',
            ],
            [
                'data' => $value,
                'name' => ucwords(str_replace(['_', '.'], ' ', $key)),
            ]
        );
    }

    public function render()
    {
        return view('kompass::livewire.settings.backend');
    }
}
