<?php

namespace Secondnetwork\Kompass\Livewire\Settings;

use Secondnetwork\Kompass\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str; // Import Str for startsWith

class PageInformation extends Component
{
    use WithFileUploads;

    public $webtitle;
    public $supline;
    public $description;
    public $image;
    public $footer_textarea;
    public $email_address;
    public $phone;
    public $copyright;

    private $imageKey = 'ogimage_src';

    protected $listeners = ['component:refresh' => '$refresh'];

    public function mount()
    {
        $globalSettings = Setting::global()->get()->keyBy('key');

        $this->webtitle = optional($globalSettings->get('webtitle'))->data ?? 'Webseite';
        $this->supline = optional($globalSettings->get('supline'))->data ?? 'Textline';
        $this->description = optional($globalSettings->get('description'))->data ?? 'Description';
        $this->footer_textarea = optional($globalSettings->get('footer_textarea'))->data ?? '';
        $this->email_address = optional($globalSettings->get('email_address'))->data ?? '';
        $this->phone = optional($globalSettings->get('phone'))->data ?? '';
        $this->copyright = optional($globalSettings->get('copyright'))->data ?? '';

        $this->image = optional($globalSettings->get($this->imageKey))->data ?? '';
    }

    public function updating($property, $value)
    {
        if ($property == 'image') {
            if ($value instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                 $filename = $value->getClientOriginalName();
                 $extension = $value->getClientOriginalExtension();
                 $newFilename = 'ogimage.' . $extension;

                 $path = $value->storeAs('images', $newFilename, 'public');

                 $publicPath = Storage::disk('public')->url($path);

                 $this->image = $publicPath;

                 $this->updateSettingInDatabase($this->imageKey, $publicPath);
            }
            return;
        }

        $this->updateSettingInDatabase($property, $value);
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
    }

    public function deleteImage()
    {
        $imagePath = $this->image;

        if ($imagePath && Str::startsWith($imagePath, '/storage/')) {
             $relativePath = str_replace('/storage/', '', $imagePath);
             if (Storage::disk('public')->exists($relativePath)) {
                 Storage::disk('public')->delete($relativePath);
             }
        }

        $this->updateSettingInDatabase($this->imageKey, '');

        $this->image = '';

        //$this->js('savedMessageOpen()');
    }

    public function render()
    {
        return view('kompass::livewire.settings.page-information');
    }
}