<?php

namespace Secondnetwork\Kompass\Livewire\Settings;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

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

    protected $listeners = ['component:refresh' => '$refresh'];

    public function mount()
    {
        $this->webtitle = config('kompass.settings.webtitle');
        $this->supline = config('kompass.settings.supline');
        $this->description = config('kompass.settings.description');
        $this->footer_textarea = config('kompass.settings.footer_textarea');
        $this->email_address = config('kompass.settings.email_address');
        $this->phone = config('kompass.settings.phone');
        $this->copyright = config('kompass.settings.copyright');
        $this->image = config('kompass.settings.image_src');
    }

    public function updating($property, $value)
    {

        if ($property == 'image') {

            $filename = $value->getFileName();
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $newFilename = 'ogimage.' . $extension;

            Storage::disk('public')->put('images/' . $newFilename, $value->get()); 
            
            $this->image = '/storage/imgaes/ogimage.'.$extension;

            $this->updateConfigKeyValue('image_src', '/storage/imgaes/ogimage.'.$extension);

            $value = null;

        }
        if ($property == 'webtitle') {
            $this->updateConfigKeyValue('webtitle', $value);
        }
        if ($property == 'supline') {
            $this->updateConfigKeyValue('supline', $value);
        }
        if ($property == 'description') {
            $this->updateConfigKeyValue('description', $value);
        }
        if ($property == 'footer_textarea') {
            $this->updateConfigKeyValue('footer_textarea', $value);
        }
        if ($property == 'email_address') {
            $this->updateConfigKeyValue('email_address', $value);
        }
        if ($property == 'phone') {
            $this->updateConfigKeyValue('phone', $value);
        }
        if ($property == 'copyright') {
            $this->updateConfigKeyValue('copyright', $value);
        }
    }

    private function updateConfigKeyValue($key, $value)
    {

        \Config::write('kompass.settings.'.$key, $value);
        Artisan::call('config:clear');
        $this->dispatch('component:refresh');
        // $this->js('savedMessageOpen()');
    }

    public function deleteImage()
    {
        $imagePath = config('kompass.settings.image_src');
        if ($imagePath && file_exists(public_path($imagePath))) {
            unlink(public_path($imagePath));
        }
        $this->updateConfigKeyValue('image_src', '');
        $this->image = '';
    }

    public function render()
    {
        return view('kompass::livewire.settings.page-information');
    }
}
