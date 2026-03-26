<?php

namespace Secondnetwork\Kompass\Livewire\Settings;

use Livewire\Component;
use Secondnetwork\Kompass\Models\Setting;

class Backendmenu extends Component
{
    public $show_posts = true;
    public $show_categories = true;
    public $show_pages = true;
    public $show_medialibrary = true;

    public function mount()
    {
        $globalSettings = Setting::global()->get()->keyBy('key');

        $this->show_posts = (bool) optional($globalSettings->get('show_posts'))->data ?? true;
        $this->show_categories = (bool) optional($globalSettings->get('show_categories'))->data ?? true;
        $this->show_pages = (bool) optional($globalSettings->get('show_pages'))->data ?? true;
        $this->show_medialibrary = (bool) optional($globalSettings->get('show_medialibrary'))->data ?? true;
    }

    public function updating($property, $value)
    {
        if (in_array($property, ['show_posts', 'show_categories', 'show_pages', 'show_medialibrary'])) {
            $this->updateSettingInDatabase($property, (string) $value);
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

    public function render()
    {
        return view('kompass::livewire.settings.backendmenu');
    }
}