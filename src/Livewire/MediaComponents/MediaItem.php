<?php

namespace Secondnetwork\Kompass\Livewire\MediaComponents;

use Livewire\Component;
use Secondnetwork\Kompass\Models\File;
use Livewire\Attributes\Locked;

class MediaItem extends Component
{
    #[Locked]
    public $file;

    public function mount(File $file)
    {
        $this->file = $file;
    }

    public function select()
    {
        $this->dispatch('file-selected', fileId: $this->file->id);
    }

    public function selectField($fileId)
    {
        $this->dispatch('media-select-field', fileId: $fileId);
    }

    public function render()
    {
        return view('kompass::livewire.medialibrary.media-item');
    }
}
