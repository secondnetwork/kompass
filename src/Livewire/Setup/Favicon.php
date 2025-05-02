<?php

namespace Secondnetwork\Kompass\Livewire\Setup;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Secondnetwork\Kompass\Features\FaviconGenerator;
use Secondnetwork\Kompass\Models\Setting;
use Illuminate\Support\Str;

class Favicon extends Component
{
    use WithFileUploads;

    public $favicon_light;
    public $favicon_dark;
    public $color_theme;

    private $dbKeyFaviconLight = 'favicon_light_image_path';
    private $dbKeyFaviconDark = 'favicon_dark_image_path';
    private $dbKeyColorTheme = 'favicon_theme_color';

    public function mount()
    {
        $globalSettings = Setting::global()->get()->keyBy('key');

        $this->favicon_light = optional($globalSettings->get($this->dbKeyFaviconLight))->data ?? '';
        $this->favicon_dark = optional($globalSettings->get($this->dbKeyFaviconDark))->data ?? '';
        $this->color_theme = optional($globalSettings->get($this->dbKeyColorTheme))->data ?? '#ffffff';
    }

    public function updated($property, $value)
    {
        $storage = Storage::disk('public');

        if ($property == 'favicon_light') {

            $this->deleteFaviconFile($this->favicon_light);

            $filename = $value->getFileName();
            $extension = $value->getClientOriginalExtension();
            $newFilename = 'favicon.'.$extension;

            $this->favicon_light = '/storage/favicon/favicon.'.$extension;
            $this->updateSettingInDatabase($this->dbKeyFaviconLight, '/storage/favicon/favicon.'.$extension);

            $storage->put('favicon/'.$newFilename, $value->get());

            $value = null;

            $favicon = new FaviconGenerator(public_path('/storage/favicon/favicon.'.$extension));
            $favicon->generateFaviconsFromImagePath();
        }

        if ($property == 'favicon_dark') {
            $this->deleteFaviconFile($this->favicon_dark);

            $filename = $value->getFileName();
            $extension = $value->getClientOriginalExtension();
            $newFilename = 'favicon-dark.'.$extension;

            $this->favicon_dark = '/storage/favicon/'.$newFilename;

            $this->updateSettingInDatabase($this->dbKeyFaviconDark, '/storage/favicon/'.$newFilename);
            
            $storage->put('favicon/'.$newFilename, $value->get());

            $value = null;
        }

        if ($property == 'color_theme') {
            $this->updateSettingInDatabase($this->dbKeyColorTheme, $value);
        }
    }

    public function updatingColorTheme($value)
    {
        $this->updateSettingInDatabase($this->dbKeyColorTheme, $value);
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

    private function deleteFaviconFile(?string $publicPath)
    {
        if ($publicPath && Str::startsWith($publicPath, '/storage/')) {
             $relativePath = str_replace('/storage/', '', $publicPath);
             if (Storage::disk('public')->exists($relativePath)) {
                 Storage::disk('public')->delete($relativePath);
             }
        }
    }

    public function deleteFaviconLight()
    {
        $this->deleteFaviconFile($this->favicon_light);
        $this->updateSettingInDatabase($this->dbKeyFaviconLight, '');
        $this->favicon_light = '';
    }

     public function deleteFaviconDark()
    {
        $this->deleteFaviconFile($this->favicon_dark);
        $this->updateSettingInDatabase($this->dbKeyFaviconDark, '');
        $this->favicon_dark = '';
    }

    public function render()
    {
        return view('kompass::livewire.setup.favicon');
    }
}
