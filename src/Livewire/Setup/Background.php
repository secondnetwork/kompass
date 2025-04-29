<?php

namespace Secondnetwork\Kompass\Livewire\Setup;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class Background extends Component
{
    use WithFileUploads;

    public $color;

    public $imageBG;

    public $image_overlay_color;

    public $image_overlay_opacity;

    public function mount()
    {
        $this->color = config('kompass.appearance.background.color');
        $this->imageBG = config('kompass.appearance.background.image');
        $this->image_overlay_color = (config('kompass.appearance.background.image_overlay_color'));
        $this->image_overlay_opacity = (config('kompass.appearance.background.image_overlay_opacity') * 100);
    }

    public function updatingColor($value)
    {
        $this->updateConfigKeyValue('background.color', $value);
    }

    public function updatingImageOverlayOpacity($value)
    {
        $this->updateConfigKeyValue('background.image_overlay_opacity', (string) floatval($value / 100));
    }

    public function updatingImageOverlayColor($value)
    {
        $this->updateConfigKeyValue('background.image_overlay_color', $value);
    }

    public function updated($property, $value)
    {

        if ($property == 'imageBG') {

            $filename = $value->getFileName();
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $newFilename = 'admin_background.'.$extension;

            Storage::disk('public')->put('images/auth/'.$newFilename, $value->get());

            $this->updateConfigKeyValue('background.image', '/storage/images/auth/'.$newFilename);

            $value = null;

        }
    }

    private function updateConfigKeyValue($key, $value)
    {
        \Config::write('kompass.appearance.'.$key, $value);
        Artisan::call('config:clear');

        // $this->js('savedMessageOpen()');
    }

    public function deleteImage()
    {
        $imagePath = config('kompass.appearance.background.image');
        if ($imagePath && file_exists(public_path($imagePath))) {
            unlink(public_path($imagePath));
        }
        $this->updateConfigKeyValue('background.image', '');
        $this->imageBG = '';
    }

    public function render()
    {
        return view('kompass::livewire.setup.background');
    }
}
