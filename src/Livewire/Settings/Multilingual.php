<?php

namespace Secondnetwork\Kompass\Livewire\Settings;

use Livewire\Component;
use Secondnetwork\Kompass\Models\Setting;
use Illuminate\Support\Facades\Cache;

class Multilingual extends Component
{
    public $multilingual;
    public $available_locales = [];
    public $new_locale;
    public $all_locales = [
        ['name' => 'German', 'id' => 'de'],
        ['name' => 'English', 'id' => 'en'],
        ['name' => 'Turkish', 'id' => 'tr'],
        ['name' => 'French', 'id' => 'fr'],
        ['name' => 'Spanish', 'id' => 'es'],
        ['name' => 'Italian', 'id' => 'it'],
        ['name' => 'Portuguese', 'id' => 'pt'],
        ['name' => 'Dutch', 'id' => 'nl'],
        ['name' => 'Polish', 'id' => 'pl'],
        ['name' => 'Russian', 'id' => 'ru'],
        ['name' => 'Chinese', 'id' => 'zh'],
        ['name' => 'Japanese', 'id' => 'ja'],
        ['name' => 'Bulgarian', 'id' => 'bg'],
        ['name' => 'Czech', 'id' => 'cs'],
        ['name' => 'Danish', 'id' => 'da'],
        ['name' => 'Greek', 'id' => 'el'],
        ['name' => 'Estonian', 'id' => 'et'],
        ['name' => 'Finnish', 'id' => 'fi'],
        ['name' => 'Irish', 'id' => 'ga'],
        ['name' => 'Croatian', 'id' => 'hr'],
        ['name' => 'Hungarian', 'id' => 'hu'],
        ['name' => 'Lithuanian', 'id' => 'lt'],
        ['name' => 'Latvian', 'id' => 'lv'],
        ['name' => 'Maltese', 'id' => 'mt'],
        ['name' => 'Romanian', 'id' => 'ro'],
        ['name' => 'Slovak', 'id' => 'sk'],
        ['name' => 'Slovenian', 'id' => 'sl'],
        ['name' => 'Swedish', 'id' => 'sv'],
        ['name' => 'Norwegian', 'id' => 'no'],
        ['name' => 'Icelandic', 'id' => 'is'],
        ['name' => 'Serbian', 'id' => 'sr'],
        ['name' => 'Bosnian', 'id' => 'bs'],
        ['name' => 'Albanian', 'id' => 'sq'],
        ['name' => 'Macedonian', 'id' => 'mk'],
        ['name' => 'Ukrainian', 'id' => 'uk'],
    ];

    public function mount()
    {
        $globalSettings = Setting::global()->get()->keyBy('key');

        $this->multilingual = (bool) optional($globalSettings->get('multilingual'))->data ?? false;
        
        $localesData = optional($globalSettings->get('available_locales'))->data;
        if ($localesData) {
            $this->available_locales = is_array($localesData) ? $localesData : json_decode($localesData, true);
        } else {
            // Default locales if none set
            $this->available_locales = ['de', 'en', 'tr'];
        }
    }

    public function updatedMultilingual($value)
    {
        $this->updateSettingInDatabase('multilingual', $value);
    }

    public function addLocale()
    {
        $this->validate();

        $locale = strtolower($this->new_locale);
        $this->available_locales[] = $locale;
        $this->saveLocales();
        $this->new_locale = '';
    }

    public function removeLocale($locale)
    {
        $this->available_locales = array_values(array_filter($this->available_locales, fn($l) => $l !== $locale));
        $this->saveLocales();
    }

    private function saveLocales()
    {
        $this->updateSettingInDatabase('available_locales', json_encode($this->available_locales));
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
                'name' => ucwords(str_replace('_', ' ', $key)),
            ]
        );
        Cache::forget('settings');
    }

    protected function rules()
    {
        return [
            'new_locale' => [
                'required',
                'string',
                'min:2',
                'max:5',
                function ($attribute, $value, $fail) {
                    if (in_array(strtolower($value), $this->available_locales)) {
                        $fail(__('This language is already added.'));
                    }
                },
            ],
        ];
    }

    public function render()
    {
        return view('kompass::livewire.settings.multilingual');
    }
}
