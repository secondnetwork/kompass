<?php

namespace Secondnetwork\Kompass\Livewire\Setup;

use Livewire\Component;
use Livewire\WithFileUploads;
use Secondnetwork\Kompass\Models\Setting;

class Color extends Component
{
    use WithFileUploads;

    public $text_color;
    public $button_color;
    public $button_text_color;
    public $input_text_color;
    public $input_border_color;

    // Definiere die Datenbank-Keys
    private $dbKeyTextColor = 'color_text';
    private $dbKeyButtonColor = 'color_button';
    private $dbKeyButtonTextColor = 'color_button_text';
    private $dbKeyInputTextColor = 'color_input_text';
    private $dbKeyInputBorderColor = 'color_input_border';


    public function mount()
    {
        $globalSettings = Setting::global()->get()->keyBy('key');

        $this->text_color = optional($globalSettings->get($this->dbKeyTextColor))->data ?? '#000000';
        $this->button_color = optional($globalSettings->get($this->dbKeyButtonColor))->data ?? '#000000';
        $this->button_text_color = optional($globalSettings->get($this->dbKeyButtonTextColor))->data ?? '#ffffff';
        $this->input_text_color = optional($globalSettings->get($this->dbKeyInputTextColor))->data ?? '#000000';
        $this->input_border_color = optional($globalSettings->get($this->dbKeyInputBorderColor))->data ?? '#e5e7eb';
    }

    public function updatingTextColor($value)
    {
        $this->updateSettingInDatabase($this->dbKeyTextColor, $value);
    }

    public function updatingButtonColor($value)
    {
        $this->updateSettingInDatabase($this->dbKeyButtonColor, $value);
    }

    public function updatingButtonTextColor($value)
    {
        $this->updateSettingInDatabase($this->dbKeyButtonTextColor, $value);
    }

    public function updatingInputTextColor($value)
    {
        $this->updateSettingInDatabase($this->dbKeyInputTextColor, $value);
    }

    public function updatingInputBorderColor($value)
    {
        $this->updateSettingInDatabase($this->dbKeyInputBorderColor, $value);
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
        // $this->js('savedMessageOpen()');
    }

    public function render()
    {
        return view('kompass::livewire.setup.color');
    }
}