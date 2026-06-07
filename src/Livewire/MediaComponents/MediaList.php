<?php

namespace Secondnetwork\Kompass\Livewire\MediaComponents;

use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Models\File;

class MediaList extends Component
{
    use WithPagination;

    #[Reactive]
    #[Url]
    public $dir = 'media';

  
    #[Url]
    public $filter = null;

    #[Url]
    public $search = '';

    public function updatedDir()
    {
        $this->resetPage();
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }

    #[On('refresh-media-list')]
    public function refresh(): void
    {
        $this->resetPage();
    }

    public function bulkDelete(array $ids): void
    {
        $disk = config('kompass.storage.disk', 'public');

        File::whereIn('id', $ids)->each(function (File $file) use ($disk) {
            if ($file->type === 'folder') {
                $full_path = ($file->path ? rtrim($file->path, '/') . '/' : '') . $file->slug;
                Storage::disk($disk)->deleteDirectory($full_path);
            } else {
                $directory = $file->path ? $file->path . '/' : '';
                Storage::disk($disk)->delete([
                    $directory . $file->slug . '.' . $file->extension,
                    $directory . $file->slug . '.avif',
                    $directory . $file->slug . '_thumbnail.avif',
                ]);
            }
            $file->delete();
        });

        $this->resetPage();
        $this->dispatch('refresh-media-list');
    }

    public function bulkMove(array $ids, string $targetPath): void
    {
        $disk = config('kompass.storage.disk', 'public');

        File::whereIn('id', $ids)->each(function (File $file) use ($disk, $targetPath) {
            $old = ($file->path ? rtrim($file->path, '/') . '/' : '') . $file->slug . ($file->extension ? '.' . $file->extension : '');
            $new = ($targetPath ? rtrim($targetPath, '/') . '/' : '') . $file->slug . ($file->extension ? '.' . $file->extension : '');
            if (Storage::disk($disk)->move($old, $new)) {
                $file->update(['path' => $targetPath]);
            }
        });

        $this->resetPage();
        $this->dispatch('refresh-media-list');
    }

    public function render()
    {
        $folders = collect();
        $showFolders = empty($this->filter) || $this->filter === 'folder';

        if ($showFolders) {
            $folders = File::where('path', $this->dir)
                ->where('type', 'folder')
                ->where('name', 'like', '%' . $this->search . '%')
                ->orderBy('name')
                ->get();
        }

        $filesQuery = File::where('path', $this->dir)
            ->where('name', 'like', '%' . $this->search . '%');

        if (!empty($this->filter) && $this->filter !== 'folder') {
            if ($this->filter === 'image') {
                $filesQuery->whereIn('type', ['image', 'svg']);
            } else {
                $filesQuery->where('type', $this->filter);
            }
        } else {
            $filesQuery->where('type', '!=', 'folder');
        }

        $files = $filesQuery->orderBy('created_at', 'DESC')->paginate(15);

        $allFolders = File::where('type', 'folder')->orderBy('path')->orderBy('name')->get();

        return view('kompass::livewire.medialibrary.media-list', [
            'folders' => $folders,
            'files' => $files,
            'allFolders' => $allFolders,
        ]);
    }
}
