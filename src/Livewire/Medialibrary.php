<?php

namespace Secondnetwork\Kompass\Livewire;

use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Models\Datafield;
use Secondnetwork\Kompass\Models\File;
use Secondnetwork\Kompass\Models\Post;
use Secondnetwork\Kompass\Models\Setting;

class Medialibrary extends Component
{
    use WithPagination;

    public $search = '';
    protected $queryString = ['search', 'dir', 'filter']; // Add filter to query string
    public $filter = ''; // Add filter property
    public $dir = 'media';
    public $foldername;
    public $name;
    public $fieldOrPage;
    public $file;
    public $description;
    public $iditem;
    public $extension = '';
    public $type;
    public $alt;
    public $path;
    public $field_id;
    public $block_id;
    public $FormDelete = false;
    public $FormEdit = false;
    public $FormFolder = false;
    public $newFolderLocation;

    /** @var string */
    private $filesystem;

    #[On('file-selected')]
    public function handleFileSelected($fileId)
    {
        $this->selectItem($fileId, 'edit');
    }

    #[On('add-folder')]
    public function handleAddFolder()
    {
         $this->FormFolder = true;
    }

    #[On('getIdBlock')]
    public function getIdBlock($id_field)
    {
        $this->block_id = $id_field;
    }

    #[On('getIdField_changnd')]
    public function getIdField($id_field, $fieldOrPage)
    {
        $this->field_id = $id_field;
        $this->fieldOrPage = $fieldOrPage;
    }

    #[On('media-select-field')]
    public function handleMediaSelectField($fileId, $fieldOrPage = null)
    {
        $this->selectField($fileId, $fieldOrPage ?: $this->fieldOrPage);
    }

    #[On('go-to-folder')]
    public function goToFolder($path)
    {
        $this->dir = $path;
        $this->filter = '';
        $this->search = '';
    }

    public function mount()
    {
        $this->filesystem = config('kompass.storage.disk', 'public');
    }

    public function selectItem($itemId, $action)
    {
        if ($action == 'addFolder') {
            $this->FormFolder = true;
        }
        if ($action == 'edit') {
            $model = File::findOrFail($itemId);
            $this->iditem = $itemId;
            $this->name = $model->name;
            $this->alt = $model->alt;
            $this->description = $model->description;
            $this->type = $model->type;
            $this->newFolderLocation = $model->path;
            $this->file = $model->path ? 'storage/' . $model->path . '/' . $model->slug . '.' . $model->extension : 'storage/' . $model->slug . '.' . $model->extension;
            if ($model->type == 'folder') {
                $this->file = null;
            }
            $this->FormEdit = true;
        }
        if ($action == 'delete') {
            $this->FormDelete = true;
        }
    }

    public function selectField($media_id, $fieldOrPageName)
    {
        switch ($this->fieldOrPage) {
            case 'thumbnails':
                Post::updateOrCreate(['id' => $this->field_id], ['thumbnails' => $media_id]);
                $this->dispatch('refreshmedia');
                break;
            case 'setting':
                Setting::updateOrCreate(['id' => $this->field_id], ['data' => $media_id]);
                $this->dispatch('refresh-setting');
                break;

            default:
                Datafield::updateOrCreate(
                    ['id' => $this->field_id], [
                        'data' => $media_id,
                        'type' => $this->fieldOrPage,
                        'block_id' => $this->block_id,
                        'order' => '999',
                    ],
                );
                $this->dispatch('refreshmedia');
                break;
        }
    }

    public function newFolder()
    {
        $new_slug = genSlug($this->foldername);
        $new_folder = ($this->dir ? rtrim($this->dir, '/') . '/' : '') . $new_slug;

        if (Storage::disk($this->filesystem)->makeDirectory($new_folder)) {
            File::create([
                'name' => $this->foldername,
                'slug' => $new_slug,
                'type' => 'folder',
                'path' => $this->dir,
            ]);
            $this->FormFolder = false;
            $this->reset('foldername');
            $this->dispatch('refresh-media-list');
        }
    }

    public function moveItem()
    {
        $file = File::findOrFail($this->iditem);
        $old_path = ($file->path ? rtrim($file->path, '/') . '/' : '') . $file->slug . ($file->extension ? '.' . $file->extension : '');
        $new_path = ($this->newFolderLocation ? rtrim($this->newFolderLocation, '/') . '/' : '') . $file->slug . ($file->extension ? '.' . $file->extension : '');

        if (Storage::disk($this->filesystem)->move($old_path, $new_path)) {
            $file->update(['path' => $this->newFolderLocation]);
            $this->FormEdit = false;
            $this->dispatch('refresh-media-list');
        }
    }

    public function update()
    {
        $File = File::findOrFail($this->iditem);
        $validateData = $this->validate([
            'name' => 'required',
            'alt' => '',
            'description' => '',
        ]);
        $File->update($validateData);
        $this->FormEdit = false;
    }

    public function delete()
    {
        $file = File::findOrFail($this->iditem);
        $diskName = $this->filesystem ?: config('kompass.storage.disk', 'public');
        
        if ($file->type == 'folder') {
            $full_path = ($file->path ? rtrim($file->path, '/') . '/' : '') . $file->slug;
            Storage::disk($diskName)->deleteDirectory($full_path);
        } else {
            $directory = $file->path ? $file->path . '/' : '';
            $filesToDelete = [
                $directory . $file->slug . '.' . $file->extension,
                $directory . $file->slug . '.avif',
                $directory . $file->slug . '_thumbnail.avif',
            ];
            Storage::disk($diskName)->delete($filesToDelete);
        }
        
        $file->delete();
        $this->FormDelete = false;
        $this->FormEdit = false;
        $this->dispatch('refresh-media-list');
    }

    public function folder_dir()
    {
        return File::where('type', 'folder')->orderBy('path')->get();
    }

    public function render()
    {
        return view('kompass::livewire.medialibrary', [
            'dirgroup' => $this->folder_dir(),
        ])->layout('kompass::admin.layouts.app');
    }
}
