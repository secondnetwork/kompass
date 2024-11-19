<?php

namespace Secondnetwork\Kompass\Livewire\Setup;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Livewire\WithFileUploads;

class Logo extends Component
{
    use WithFileUploads;

    public $logo_type;

    public $logo_image_src;

    public $logo_svg_string;

    public $logo_height;

    public $logo_image;

    public function mount()
    {

        $this->logo_type = config('kompass.appearance.logo.type');
        $this->logo_image_src = config('kompass.appearance.logo.image_src');
        $this->logo_svg_string = config('kompass.appearance.logo.svg_string');
        $this->logo_height = config('kompass.appearance.logo.height');

        if ($this->logo_image_src) {
            $this->logo_image = true;
        }

    }

    protected function rules()
    {

        return [
            'logo_type' => 'required',
            'logo_image_src' => 'required',
            'logo_image_string' => 'required',
            'logo_height' => 'required',
        ];
    }

    public function updateSvg($value)
    {
        $this->updateConfigKeyValue('logo.svg_string', $value);
    }

    public function updating($property, $value)
    {
        if ($property == 'logo_image') {

            $filename = $value->getFileName();
            $extension = pathinfo($filename, PATHINFO_EXTENSION);

            $value->storeAs('public/auth', 'logo.'.$extension);
            $this->logo_image_src = '/storage/logo.'.$extension;

            $this->updateConfigKeyValue('logo.image_src', '/storage/logo.'.$extension);

            $value = null;
        }

        if ($property == 'logo_type') {
            $this->updateConfigKeyValue('logo.type', $value);
        }

        if ($property == 'logo_height') {
            $this->updateConfigKeyValue('logo.height', $value);
        }
    }

    private function updateConfigKeyValue($key, $value)
    {

        \Config::write('kompass.appearance.'.$key, $value);
        Artisan::call('config:clear');

        // $this->js('savedMessageOpen()');
    }

    public function logoValue()
    {

        $logo = match ($this->logo_type) {
            'image' => $this->logo_image,
            'svg' => $this->logo_svg_string,
            'text' => $this->logo_image_src
        };

        return $logo;
    }

    public function render()
    {
        return view('kompass::livewire.setup.logo');
    }
}
