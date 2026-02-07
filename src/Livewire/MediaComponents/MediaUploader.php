<?php

namespace Secondnetwork\Kompass\Livewire\MediaComponents;

use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Secondnetwork\Kompass\Models\File;

class MediaUploader extends Component
{
    use WithFileUploads;

    #[Validate(['files.*' => 'image|max:10240'])]
    public array $files = [];

    #[Reactive]
    public $dir = 'media';

    public int $progress = 0;

    public function updatedFiles()
    {
        $this->validateOnly('files');

        $filesystem = config('kompass.storage.disk', 'public');

        foreach ($this->files as $filedata) {
            $filename = pathinfo($filedata->getClientOriginalName(), PATHINFO_FILENAME);
            $original_ext = $filedata->getClientOriginalExtension();

            $filesSlug = genSlug($filename);
            $time = date('Y_m_d');
            $timefilesSlug = $time.'_'.$filesSlug;

            $fileModel = new File;
            $type = $fileModel->getType($original_ext);

            $storelink = $filedata->storeAs(
                $this->dir,
                $timefilesSlug.'.'.$original_ext,
                $filesystem
            );

            if ($storelink) {
                File::create([
                    'path' => $this->dir,
                    'name' => $filename,
                    'slug' => $timefilesSlug,
                    'extension' => $original_ext,
                    'type' => $type,
                    'alt' => $filename,
                    'description' => '',
                ]);
            }
        }

        $this->reset('files');
        $this->progress = 0;
        $this->dispatch('refresh-media-list');
        $this->dispatch('$refresh');
    }

    public function render()
    {
        return view('kompass::livewire.medialibrary.media-uploader');
    }
}
