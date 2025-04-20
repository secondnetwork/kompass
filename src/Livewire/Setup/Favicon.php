<?php

namespace Secondnetwork\Kompass\Livewire\Setup;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Secondnetwork\Kompass\Features\FaviconGenerator;
class Favicon extends Component
{
    use WithFileUploads;

    public $favicon_light;

    public $favicon_dark;

    public $color_theme;

    public $filePath;

    public function mount()
    {
        $this->favicon_light = config('kompass.appearance.favicon.light');
        $this->favicon_dark = config('kompass.appearance.favicon.dark');
        $this->color_theme = config('kompass.appearance.favicon.color_theme');
    }

    public function updated($property, $value)
    {
        $storage = Storage::disk('public');

        if ($property == 'favicon_light') {
            $filename = $value->getFileName();
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $newFilename = 'favicon.'.$extension;

            $this->favicon_light = '/storage/favicon/favicon.'.$extension;
            $this->updateConfigKeyValue('favicon.light', '/storage/favicon/favicon.'.$extension);

            $storage->put('favicon/'.$newFilename, $value->get());

            $value = null;

            $favicon = new FaviconGenerator(public_path('/storage/favicon/favicon.'.$extension));
            $favicon->generateFaviconsFromImagePath();
        }

        if ($property == 'favicon_dark') {
            $filename = $value->getFileName();
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $newFilename = 'favicon-dark.'.$extension;

            $this->favicon_dark = '/storage/favicon/'.$newFilename;
            $this->updateConfigKeyValue('favicon.dark', '/storage/favicon/'.$newFilename);

            $storage->put('favicon/'.$newFilename, $value->get());

            $value = null;
        }

        if ($property == 'color_theme') {
            $this->updateConfigKeyValue('favicon.color_theme', $value);
        }
    }

    private function updateConfigKeyValue($key, $value)
    {
        \Config::write('kompass.appearance.'.$key, $value);
        Artisan::call('config:clear');
        // $this->js('savedMessageOpen()');
    }

    public function render()
    {
        return view('kompass::livewire.setup.favicon');
    }
}
