<?php

namespace Secondnetwork\Kompass\Livewire\Settings;

use Livewire\Attributes\On;
use Livewire\Component;
use Secondnetwork\Kompass\Models\Setting;

class Backend extends Component
{
    public $registration_can_user;

    public $password_login_enabled;

    public $admincopyright;

    public $adminlogo;

    public $dashboard_docs_card;

    public $FormMedia = false;

    public $getId;

    private $dbKeyRegistration = 'registration_can_user';

    private $imageKey = 'adminlogo';

    public function mount()
    {
        $globalSettings = Setting::global()->get()->keyBy('key');

        $this->registration_can_user = (bool) optional($globalSettings->get($this->dbKeyRegistration))->data ?? false;
        $this->password_login_enabled = optional($globalSettings->get('password_login_enabled'))->data !== null
            ? (bool) optional($globalSettings->get('password_login_enabled'))->data
            : config('kompass.auth.password_login_enabled', false);
        $this->admincopyright = optional($globalSettings->get('admincopyright'))->data ?? '';
        $this->dashboard_docs_card = optional($globalSettings->get('dashboard_docs_card'))->data !== null
            ? (bool) optional($globalSettings->get('dashboard_docs_card'))->data
            : true;

        $logoSetting = $globalSettings->get($this->imageKey)
            ?? Setting::create([
                'key' => $this->imageKey,
                'group' => 'global',
                'name' => ucwords(str_replace(['_', '.'], ' ', $this->imageKey)),
            ]);

        $this->getId = $logoSetting->id;
        $this->adminlogo = Setting::resolveImageUrl($logoSetting->data) ?? '';
    }

    public function updating($property, $value)
    {
        if ($property === 'registration_can_user') {
            $this->updateSettingInDatabase($this->dbKeyRegistration, (string) $value);
        }
        if ($property === 'password_login_enabled') {
            $this->updateSettingInDatabase('password_login_enabled', (string) $value);
        }
        if ($property === 'admincopyright') {
            $this->updateSettingInDatabase('admincopyright', $value);
        }
        if ($property === 'dashboard_docs_card') {
            $this->updateSettingInDatabase('dashboard_docs_card', (string) $value);
        }
    }

    public function selectItem($itemId, $action)
    {
        if ($action === 'addMedia') {
            $this->getId = $itemId;
            $this->FormMedia = true;
            $this->dispatch('getIdField_changnd', $this->getId, 'setting');
        }
    }

    #[On('refresh-setting')]
    public function refreshLogo()
    {
        $this->FormMedia = false;

        $setting = Setting::find($this->getId);
        $this->adminlogo = Setting::resolveImageUrl($setting?->data) ?? '';
    }

    public function removemedia($id)
    {
        Setting::whereId($id)->update(['data' => null]);
        $this->adminlogo = '';
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
