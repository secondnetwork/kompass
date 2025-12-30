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

  
    public $filter = ''; // Add filter property

    public $search = '';
    protected $queryString = ['search', 'dir', 'filter']; // Add filter to query string

    public function updatedDir()
    {
        $this->resetPage();
    }

    public function updatedFilter()
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
        $query = File::where('path', $this->dir)
                      ->where('name', 'like', '%' . $this->search . '%');

        // Apply filter if selected
        if (!empty($this->filter)) {
            if ($this->filter === 'folder') {
                $query->where('type', 'folder');
            } elseif ($this->filter === 'image') {
                $query->whereIn('type', ['image', 'svg']);
            } else {
                $query->where('type', $this->filter);
            }
        }

        $files = $query->orderBy('created_at', 'DESC')
                       ->paginate(24);

        return view('kompass::livewire.medialibrary.media-list', [
            'files' => $files,
        ]);
    }
}
