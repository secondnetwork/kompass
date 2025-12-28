<?php

namespace Secondnetwork\Kompass\Livewire\MediaComponents;

use Livewire\Component;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Models\File;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;

class MediaList extends Component
{
    use WithPagination;

    #[Reactive]
    public $dir = 'media';

    public $search = '';

    protected $queryString = ['search'];

    public function updatedDir()
    {
        $this->resetPage();
    }

    #[On('refresh-media-list')]
    public function refresh()
    {
        $this->resetPage();
    }

    public function render()
    {
        $files = File::where('path', $this->dir)
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'DESC')
            ->paginate(24);

        return view('kompass::livewire.medialibrary.media-list', [
            'files' => $files,
        ]);
    }
}
