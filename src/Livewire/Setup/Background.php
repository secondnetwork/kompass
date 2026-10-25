<?php

namespace Secondnetwork\Kompass\Livewire\Setup;

use Livewire\Attributes\On;
use Livewire\Component;
use Secondnetwork\Kompass\Models\Setting;

class Background extends Component
{
    public $adminBackground;

    public $image_overlay_color;

    public $FormMedia = false;

    public $getId;

    private $dbKeyImage = 'background_image';

    private $dbKeyOverlayColor = 'background_image_overlay_color';

    #[On('component:refresh')]
    public function handleRefresh(): void {}

    public function mount()
    {
        $globalSettings = Setting::global()->get()->keyBy('key');

        $this->image_overlay_color = optional($globalSettings->get($this->dbKeyOverlayColor))->data ?? '';

        // The image is picked from the media library, so the setting stores the
        // media file id. Create the row on the fly to get an id for the picker.
        $imageSetting = $globalSettings->get($this->dbKeyImage)
            ?? Setting::create([
                'key' => $this->dbKeyImage,
                'group' => 'global',
                'name' => ucwords(str_replace(['_', '.'], ' ', $this->dbKeyImage)),
            ]);

        $this->getId = $imageSetting->id;
        $this->adminBackground = Setting::resolveImageUrl($imageSetting->data) ?? '';
    }

    public function updatingImageOverlayColor($value)
    {
        $this->updateSettingInDatabase($this->dbKeyOverlayColor, $value);
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
    public function refreshBackgroundImage()
    {
        $this->FormMedia = false;

        $setting = Setting::find($this->getId);
        $this->adminBackground = Setting::resolveImageUrl($setting?->data) ?? '';
    }

    public function removemedia($id)
    {
        Setting::whereId($id)->update(['data' => null]);
        $this->adminBackground = '';
    }

    /**
     * Saves or updates a setting key/value pair in the database.
     */
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
        return view('kompass::livewire.setup.background');
    }
}
