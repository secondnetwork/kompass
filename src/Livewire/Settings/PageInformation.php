<?php

namespace Secondnetwork\Kompass\Livewire\Settings;

use Livewire\Attributes\On;
use Livewire\Component;
use Secondnetwork\Kompass\Models\Setting;

class PageInformation extends Component
{
    public $webtitle;

    public $supline;

    public $description;

    public $image;

    public $footer_textarea;

    public $email_address;

    public $phone;

    public $copyright;

    public $FormMedia = false;

    public $getId;

    private $imageKey = 'ogimage_src';

    #[On('component:refresh')]
    public function handleRefresh(): void {}

    public function mount()
    {
        $globalSettings = Setting::global()->get()->keyBy('key');

        $this->webtitle = optional($globalSettings->get('webtitle'))->data ?? 'Webseite';
        $this->supline = optional($globalSettings->get('supline'))->data ?? 'Textline';
        $this->description = optional($globalSettings->get('description'))->data ?? 'Description';
        $this->footer_textarea = optional($globalSettings->get('footer_textarea'))->data ?? '';
        $this->email_address = optional($globalSettings->get('email_address'))->data ?? '';
        $this->phone = optional($globalSettings->get('phone'))->data ?? '';
        $this->copyright = optional($globalSettings->get('copyright'))->data ?? '';

        $ogImageSetting = $globalSettings->get($this->imageKey)
            ?? Setting::create([
                'key' => $this->imageKey,
                'group' => 'global',
                'name' => ucwords(str_replace('_', ' ', $this->imageKey)),
            ]);

        $this->getId = $ogImageSetting->id;
        $this->image = Setting::resolveImageUrl($ogImageSetting->data) ?? '';
    }

    public function updating($property, $value)
    {
        if (in_array($property, ['image', 'FormMedia', 'getId'], true)) {
            return;
        }

        $this->updateSettingInDatabase($property, $value);
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
    public function refreshImage()
    {
        $this->FormMedia = false;

        $setting = Setting::find($this->getId);
        $this->image = Setting::resolveImageUrl($setting?->data) ?? '';
    }

    public function removemedia($id)
    {
        Setting::whereId($id)->update(['data' => null]);
        $this->image = '';
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
                'name' => ucwords(str_replace('_', ' ', $key)),
            ]
        );
    }

    public function render()
    {
        return view('kompass::livewire.settings.page-information');
    }
}
