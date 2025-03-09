<?php

namespace Secondnetwork\Kompass\Livewire\Setup;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Livewire\WithFileUploads;

class Background extends Component
{
    use WithFileUploads;

    public $color;

    public $image;

    public $image_overlay_color;

    public $image_overlay_opacity;

    public function mount()
    {
        $this->color = config('kompass.appearance.background.color');
        $this->image = config('kompass.appearance.background.image');
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
        if ($property == 'image') {
            $filename = $value->getFileName();
            $extension = pathinfo($filename, PATHINFO_EXTENSION);

            $value->storeAs('public/auth', 'background.'.$extension);
            $this->image = '/storage/auth/background.'.$extension;

            $this->updateConfigKeyValue('background.image', '/storage/background.'.$extension);

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
        $this->image = '';
    }

    public function render()
    {
        return view('kompass::livewire.setup.background');
    }
}
