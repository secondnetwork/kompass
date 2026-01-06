<?php

namespace Secondnetwork\Kompass\Livewire\Settings;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Secondnetwork\Kompass\Models\Setting;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

class AdminPanel extends Component
{
    use WithFileUploads;

    public $logo;
    public $newLogo;
    public $copyright;

    public function mount()
    {
        $this->logo = setting('global.logo');
        $this->copyright = setting('global.copyright');
    }

    public function save()
    {
        if ($this->newLogo) {
            $extension = $this->newLogo->getClientOriginalExtension();
            $newFilename = 'logo.' . $extension;
            
            $path = $this->newLogo->storeAs('images', $newFilename, 'public');
            $this->logo = Storage::disk('public')->url($path);
        }

        if ($this->logo) {
             Setting::updateOrCreate(
                ['key' => 'logo', 'group' => 'global'],
                ['data' => $this->logo, 'name' => 'Admin Logo', 'type' => 'image']
            );
        }
        
        if ($this->copyright) {
            Setting::updateOrCreate(
                ['key' => 'copyright', 'group' => 'global'],
                ['data' => $this->copyright, 'name' => 'Copyright', 'type' => 'text']
            );
        }

        $this->dispatch('saved');
    }

    public function deleteLogo()
    {
        $imagePath = $this->logo;

        if ($imagePath && Str::startsWith($imagePath, '/storage/')) {
             $relativePath = str_replace('/storage/', '', $imagePath);
             if (Storage::disk('public')->exists($relativePath)) {
                 Storage::disk('public')->delete($relativePath);
             }
        }

        $this->logo = '';
        $this->newLogo = null;

        Setting::updateOrCreate(
            ['key' => 'logo', 'group' => 'global'],
            ['data' => '', 'name' => 'Admin Logo', 'type' => 'image']
        );

        $this->dispatch('saved');
    }

    #[Layout('kompass::admin.layouts.app')]
    public function render()
    {
        return view('kompass::livewire.settings.admin-panel');
    }
}
