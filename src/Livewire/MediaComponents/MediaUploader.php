<?php

namespace Secondnetwork\Kompass\Livewire\MediaComponents;

use Livewire\Component;
use Livewire\WithFileUploads;
use Secondnetwork\Kompass\Models\File;
use Illuminate\Support\Facades\Storage;

class MediaUploader extends Component
{
    use WithFileUploads;

    public $files = [];
    public $dir = 'media';

    public function updatedFiles()
    {
        $filesystem = config('kompass.storage.disk', 'public');

        foreach ($this->files as $filedata) {
            $filename = pathinfo($filedata->getClientOriginalName(), PATHINFO_FILENAME);
            $original_ext = $filedata->getClientOriginalExtension();
            
            $filesSlug = genSlug($filename);
            $time = date('Y_m_d'); 
            $timefilesSlug = $time . '_' . $filesSlug;
            
            $fileModel = new File();
            $type = $fileModel->getType($original_ext);

            $storelink = $filedata->storeAs(
                $this->dir, 
                $timefilesSlug . '.' . $original_ext, 
                $filesystem
            );

            if ($storelink) {
                $file = File::create([
                    'path' => $this->dir,
                    'name' => $filename,
                    'slug' => $timefilesSlug,
                    'extension' => $original_ext,
                    'type' => $type,
                    'alt' => $filename,
                    'description' => '',
                ]);

                if ($type === 'image') {
                    // Pre-generate common sizes to avoid on-the-fly bottleneck
                    $url = Storage::disk($filesystem)->url($storelink);
                    imageToWebp($url, 500);
                    imageToWebp($url, 1000);
                }
            }
        }

        $this->reset('files');
        $this->dispatch('refresh-media-list');
        $this->dispatch('$refresh');
    }

    public function render()
    {
        return view('kompass::livewire.medialibrary.media-uploader');
    }
}
