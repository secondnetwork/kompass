<?php

namespace Secondnetwork\Kompass\Livewire\Setup;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Secondnetwork\Kompass\Models\Setting;
use Illuminate\Support\Str;

class Logo extends Component
{
    use WithFileUploads;

    public $logo_type;
    public $logo_image_src;
    public $logo_svg_string;
    public $logo_height;
    public $logo_image;

    private $dbKeyLogoType = 'logo_type';
    private $dbKeyLogoImageSrc = 'logo_image_src';
    private $dbKeyLogoSvgString = 'logo_svg_string';
    private $dbKeyLogoHeight = 'logo_height';

    public function mount()
    {
        $globalSettings = Setting::global()->get()->keyBy('key');

        $this->logo_type = optional($globalSettings->get($this->dbKeyLogoType))->data ?? 'text';
        $this->logo_image_src = optional($globalSettings->get($this->dbKeyLogoImageSrc))->data ?? '';
        $this->logo_svg_string = optional($globalSettings->get($this->dbKeyLogoSvgString))->data ?? '';
        $this->logo_height = optional($globalSettings->get($this->dbKeyLogoHeight))->data ?? '8';
    }

    public function saveSvg()
    {
        \Illuminate\Support\Facades\Log::info('saveSvg called, string: ' . $this->logo_svg_string);
        $this->updateSettingInDatabase($this->dbKeyLogoSvgString, $this->logo_svg_string);
        $this->logo_type = 'svg';
        $this->updateSettingInDatabase($this->dbKeyLogoType, 'svg');
        
        session()->flash('message', 'SVG erfolgreich gespeichert.');
    }

    public function updatedLogoImage()
    {
        if ($this->logo_image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
            $this->deleteLogoImageFile($this->logo_image_src);

            $extension = $this->logo_image->getClientOriginalExtension();
            $newFilename = 'logo.' . $extension;

            $path = $this->logo_image->storeAs('images/logo', $newFilename, 'public');
            $publicPath = Storage::disk('public')->url($path);

            $this->logo_image_src = $publicPath;
            $this->updateSettingInDatabase($this->dbKeyLogoImageSrc, $publicPath);
            
            $this->logo_type = 'image';
            $this->updateSettingInDatabase($this->dbKeyLogoType, 'image');
        }
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

    private function deleteLogoImageFile(?string $publicPath)
    {
        if ($publicPath && Str::startsWith($publicPath, '/storage/')) {
             $relativePath = str_replace('/storage/', '', $publicPath);
             if (Storage::disk('public')->exists($relativePath)) {
                 Storage::disk('public')->delete($relativePath);
             }
        }
    }

    public function deleteLogoImage()
    {
        $this->deleteLogoImageFile($this->logo_image_src);
        $this->updateSettingInDatabase($this->dbKeyLogoImageSrc, '');
        $this->logo_image_src = '';
        $this->logo_image = null;
    }

    public function render()
    {
        return view('kompass::livewire.setup.logo');
    }
}