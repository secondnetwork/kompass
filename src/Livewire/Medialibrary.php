<?php

namespace Secondnetwork\Kompass\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Models\Datafield;
use Secondnetwork\Kompass\Models\File;
use Secondnetwork\Kompass\Models\Post;
use Secondnetwork\Kompass\Models\Setting;

class Medialibrary extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $search = '';
    protected $queryString = ['search'];
    public array $metadata = [];
    public $dir = 'media';
    public $files = [];
    public $mediafiles = [];
    public $directories = [];
    public $allFiles;
    public $foldername;
    public $name;
    public $fieldOrPage;
    public $file;
    public $description;
    public $uploaded_file;
    public $photo;
    public $slugname;
    public $iditem;
    public $extension = '';
    public $type;
    public $alt;
    public $path;
    public $field_id;
    public $block_id;
    public $headers;
    public $action;
    public $selectedItem;
    public $FormDelete = false;
    public $FormAdd = false;
    public $FormEdit = false;
    public $FormFolder = false;
    public $perPage = 50;
    public $orderBy = 'created_at';
    public $orderAsc = true;
    public $newFolderLocation;

    /** @var string */
    private $filesystem;

    /** @var string */
    private $directory = '';

    #[on('getIdBlock')]
    public function getIdBlock($id_field)
    {
        $this->block_id = $id_field;
    }

    private function headerConfig()
    {
        return [
            // 'id' => '#',
            'name' => 'Name',
            'slug' => 'slug',
            'type' => 'type',
            'extension' => 'original_storelink',
            'path' => 'path',
            // 'user_id' => 'Auth::id()'
            'edit' => '',
        ];
    }

    public function mount()
    {
        $this->headers = $this->headerConfig();

        $this->filesystem = config('kompass.storage.disk');

        $this->mediafiles = file::orderBy('created_at', 'DESC')->get();

        // $this->directories = Storage::allDirectories($this->filesystem);

        // $this->directories = str_replace('public/', '', $this->directories);

        // $this->allFiles = Storage::allFiles($this->filesystem);
        // $this->datarow = $this->row();
    }

    public function selectItem($itemId, $action)
    {
        // $this->selectedItem = $itemId;

        if ($action == 'addItem') {
            // PagesData::FormEdit = false;
            // dump($this->fieldId);
            // dd(PagesData::getName());
            // $this->belongsTo('App\PagesData');
        }
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
            if ($model->path) {
                $this->file = 'storage/'.$model->path.'/'.$model->slug.'.'.$model->extension;
            } else {
                $this->file = 'storage/'.$model->slug.'.'.$model->extension;
            }

            $this->updated_at = $model->updated_at;
            $this->FormEdit = true;
        }

        if ($action == 'update') {
            // $this->dispatch('getModelId', $this->selectedItem);

            // $model = File::findOrFail($this->selectedItem);
            // $this->Rolrs = Role::all();

            // $this->name = $model->name;
            // $this->email = $model->email;
            $this->FormEdit = true;
        }

        if ($action == 'delete') {
            // This will show the modal on the frontend
            $this->FormDelete = true;
            // $this->dispatchBrowserEvent('openDeleteModal');
        }
    }

        public function moveItem()
    {
        $file = File::findOrFail($this->iditem);
        
        if ($file->path === $this->newFolderLocation) {
            return;
        }

        $diskName = $this->filesystem ?: config('kompass.storage.disk', 'public');
        $disk = Storage::disk($diskName);

        $oldPath = $file->path ? $file->path . '/' : '';
        $newPath = $this->newFolderLocation ? $this->newFolderLocation . '/' : '';
        
        $filename = $file->slug . '.' . $file->extension;
        
        // Move main file
        if ($disk->exists($oldPath . $filename)) {
            $disk->move($oldPath . $filename, $newPath . $filename);
        }
        
        // Move related files (avif, thumbnail)
        $relatedExtensions = ['.avif', '_thumbnail.avif'];
        foreach ($relatedExtensions as $ext) {
             if ($disk->exists($oldPath . $file->slug . $ext)) {
                 $disk->move($oldPath . $file->slug . $ext, $newPath . $file->slug . $ext);
             }
        }

        $file->path = $this->newFolderLocation;
        $file->save();

        $this->FormEdit = false;
        $this->dispatch('$refresh');
    }

    public function newFolder()
    {
        $new_slug = genSlug($this->foldername);
        $new_folder = ($this->dir ? rtrim($this->dir, '/') . '/' : '') . $new_slug;

        $success = false;
        $error = '';

        $this->filesystem = config('kompass.storage.disk');

        if (Storage::disk($this->filesystem)->exists($new_folder)) {
            $error = __('media.folder_exists_already');
        } elseif (Storage::disk($this->filesystem)->makeDirectory($new_folder)) {
            $success = true;
            $file = new File;
            $file::create([
                'name' => $this->foldername,
                'type' => 'folder',
                'path' => $new_folder,
                // 'user_id' => Auth::id(),
            ]);
            $this->dispatch('$refresh');
        } else {
            $error = __('media.error_creating_dir');
        }

        $this->FormFolder = false;
    }


public function updatedFiles()
{
    $this->filesystem = config('kompass.storage.disk', 'public');

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
            $this->filesystem
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

    // Aufräumen
    $this->reset('files');
    
    // Daten neu laden (oder einfach $refresh dispatchen)
    $this->mediafiles = File::orderBy('created_at', 'DESC')->get();
    
    $this->dispatch('$refresh');
    session()->flash('message', 'Upload erfolgreich.');
}

    #[on('getIdField_changnd')]
    public function getIdField($id_field, $fieldOrPage)
    {
        $this->field_id = $id_field;
        $this->fieldOrPage = $fieldOrPage;
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

    public function update()
    {
        $File = File::findOrFail($this->iditem);

        // Data validation
        $validateData = $this->validate([
            'name' => 'required',
            // 'name' => 'required|regex:/^[\pL\s\-]+$/u|min:3|max:255',
            'alt' => '',
            'description' => '',
        ]);

        $File->update($validateData);
        $this->FormEdit = false;
        $this->resetPage();
    }

    public function delete()
    {
        $file = File::findOrFail($this->iditem);

        // Determine disk (fallback to public if not set)
        $diskName = $this->filesystem ?: config('kompass.storage.disk', 'public');
        
        // Construct path relative to disk root
        $directory = $file->path ? $file->path . '/' : '';
        $mainFile = $directory . $file->slug . '.' . $file->extension;
        
        $filesToDelete = [
            $mainFile,
            $directory . $file->slug . '.avif',
            $directory . $file->slug . '_thumbnail.avif',
        ];

        // Delete from storage
        Storage::disk($diskName)->delete($filesToDelete);

        // Delete from database
        $file->delete();

        // Reset UI state
        $this->FormDelete = false;
        $this->FormEdit = false;
        
        // Refresh list
        $this->mount();
    }


    private function resultDate()
    {
        return file::where('name', 'like', '%'.$this->search.'%')->Paginate(100);
        // return file::whereLike(['name', 'description'], '%' . $this->search . '%')->Paginate(100);
    }

    public function folder_dir()
    {
        $folder_dir = file::select('path')
            ->orderBy('path')
            ->groupBy('path')
            ->get();

        return $folder_dir;
    }

    public function render()
    {
        return view('kompass::livewire.medialibrary', [
            'dirgroup' => $this->folder_dir(),
            'filessearch' => $this->resultDate(),
        ])->layout('kompass::admin.layouts.app');
    }
}
