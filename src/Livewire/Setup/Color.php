<?php

namespace Secondnetwork\Kompass\Livewire\Setup;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Livewire\WithFileUploads;

class Color extends Component
{
    use WithFileUploads;

    public $text_color;

    public $button_color;

    public $button_text_color;

    public $input_text_color;

    public $input_border_color;

    public function mount()
    {
        $this->text_color = config('kompass.appearance.color.text');
        $this->button_color = config('kompass.appearance.color.button');
        $this->button_text_color = config('kompass.appearance.color.button_text');
        $this->input_text_color = config('kompass.appearance.color.input_text');
        $this->input_border_color = config('kompass.appearance.color.input_border');
    }

    public function updatingTextColor($value)
    {
        $this->updateConfigKeyValue('color.text', $value);
    }

    public function updatingButtonColor($value)
    {
        $this->updateConfigKeyValue('color.button', $value);
    }

    public function updatingButtonTextColor($value)
    {
        $this->updateConfigKeyValue('color.button_text', $value);
    }

    public function updatingInputTextColor($value)
    {
        $this->updateConfigKeyValue('color.input_text', $value);
    }

    public function updatingInputBorderColor($value)
    {
        $this->updateConfigKeyValue('color.input_border', $value);
    }

    private function updateConfigKeyValue($key, $value)
    {
        \Config::write('kompass.appearance.'.$key, $value);
        Artisan::call('config:clear');
        $this->js('savedMessageOpen()');
    }

    public function render()
    {
        return view('kompass::livewire.setup.color');
    }
}
