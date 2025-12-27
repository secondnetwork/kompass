<?php

namespace Secondnetwork\Kompass\Livewire\MediaComponents;

use Livewire\Component;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Models\File;
use Livewire\Attributes\On;

class MediaList extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    #[On('refresh-media-list')]
    public function refresh()
    {
        $this->resetPage();
    }

    public function render()
    {
        $files = File::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'DESC')
            ->paginate(50);

        return view('kompass::livewire.medialibrary.media-list', [
            'files' => $files,
        ]);
    }
}
