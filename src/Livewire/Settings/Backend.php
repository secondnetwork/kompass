<?php

namespace Secondnetwork\Kompass\Livewire\Settings;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Secondnetwork\Kompass\Models\Setting;
use Livewire\WithFileUploads;

class Backend extends Component
{
    use WithFileUploads;

    public $registration_can_user;
    public $admincopyright;
    public $adminlogo;
    private $dbKeyRegistration = 'registration_can_user';
    private $imageKey = 'adminlogo';
    public function mount()
    {
        $globalSettings = Setting::global()->get()->keyBy('key');
        
        $this->registration_can_user = (bool) optional($globalSettings->get($this->dbKeyRegistration))->data ?? false;
        $this->admincopyright = optional($globalSettings->get('admincopyright'))->data ?? '';
        $this->adminlogo = optional($globalSettings->get($this->imageKey))->data ?? '';

    }

    public function updating($property, $value)
    {
        if ($property === 'registration_can_user') {
            $this->updateSettingInDatabase($this->dbKeyRegistration, (string) $value);
        }
        if ($property === 'copyright') {
            $this->updateSettingInDatabase($this->dbKeyCopyright, $value);
        }
        if ($property == 'adminlogo') {
        
            if ($value instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                 $filename = $value->getClientOriginalName();
                 $extension = $value->getClientOriginalExtension();
                 $newFilename = 'adminlogo.' . $extension;

                 $path = $value->storeAs('images', $newFilename, 'public');

                 $publicPath = Storage::disk('public')->url($path);

                 $this->adminlogo = $publicPath;

                 $this->updateSettingInDatabase($this->imageKey, $publicPath);
            }
            return;
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

    public function deleteImage()
    {
        $imagePath = $this->adminlogo;

        if ($imagePath && Str::startsWith($imagePath, '/storage/')) {
             $relativePath = str_replace('/storage/', '', $imagePath);
             if (Storage::disk('public')->exists($relativePath)) {
                 Storage::disk('public')->delete($relativePath);
             }
        }

        $this->updateSettingInDatabase($this->imageKey, '');

        $this->adminlogo = '';

        // $this->js('savedMessageOpen()');
    }

    public function render()
    {
        return view('kompass::livewire.settings.backend');
    }
}
