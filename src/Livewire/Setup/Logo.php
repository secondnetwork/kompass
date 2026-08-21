<?php

namespace Secondnetwork\Kompass\Livewire\Setup;

use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Secondnetwork\Kompass\Models\Setting;

class Logo extends Component
{
    public $logo_type;

    public $logo_image_src;

    public $logo_svg_string;

    public $logo_height;

    public $FormMedia = false;

    public $getId;

    private $dbKeyLogoType = 'logo_type';

    private $dbKeyLogoImageSrc = 'logo_image_src';

    private $dbKeyLogoSvgString = 'logo_svg_string';

    private $dbKeyLogoHeight = 'logo_height';

    public function mount()
    {
        $globalSettings = Setting::global()->get()->keyBy('key');

        $this->logo_type = optional($globalSettings->get($this->dbKeyLogoType))->data ?? 'text';
        $this->logo_svg_string = optional($globalSettings->get($this->dbKeyLogoSvgString))->data ?? '';
        $this->logo_height = optional($globalSettings->get($this->dbKeyLogoHeight))->data ?? '8';

        $logoImageSetting = $globalSettings->get($this->dbKeyLogoImageSrc)
            ?? Setting::create([
                'key' => $this->dbKeyLogoImageSrc,
                'group' => 'global',
                'name' => ucwords(str_replace(['_', '.'], ' ', $this->dbKeyLogoImageSrc)),
            ]);

        $this->getId = $logoImageSetting->id;
        $this->logo_image_src = Setting::resolveImageUrl($logoImageSetting->data) ?? '';
    }

    public function saveSvg()
    {
        Log::info('saveSvg called, string: '.$this->logo_svg_string);
        $this->updateSettingInDatabase($this->dbKeyLogoSvgString, $this->logo_svg_string);
        $this->logo_type = 'svg';
        $this->updateSettingInDatabase($this->dbKeyLogoType, 'svg');

        session()->flash('message', 'SVG erfolgreich gespeichert.');
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
    public function refreshLogoImage()
    {
        $this->FormMedia = false;

        $setting = Setting::find($this->getId);
        $this->logo_image_src = Setting::resolveImageUrl($setting?->data) ?? '';
        $this->logo_type = 'image';
        $this->updateSettingInDatabase($this->dbKeyLogoType, 'image');
    }

    public function removemedia($id)
    {
        Setting::whereId($id)->update(['data' => null]);
        $this->logo_image_src = '';
    }

    // Hook für einfache Felder
    public function updated($property, $value)
    {
        if ($property === 'logo_type') {
            $this->updateSettingInDatabase($this->dbKeyLogoType, $value);
        } elseif ($property === 'logo_height') {
            $this->updateSettingInDatabase($this->dbKeyLogoHeight, $value);
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
        return view('kompass::livewire.setup.logo');
    }
}
