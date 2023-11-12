<?php

namespace Secondnetwork\Kompass\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Models\Datafields;
use Secondnetwork\Kompass\Models\File;
use Secondnetwork\Kompass\Models\Setting;

class Medialibrary extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    public array $metadata = [];

    public $dir = '';

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

    /** @var string */
    private $filesystem;

    /** @var string */
    private $directory = '';

    protected $listeners = [
        'getIdField_changnd' => 'getIdField',
        'getIdBlock' => 'getIdBlock',
        'resetCom' => '$refresh',
    ];

    public function getIdField($id_field, $fieldOrPage)
    {
        $this->field_id = $id_field;
        $this->fieldOrPage = $fieldOrPage;
    }

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
            dump($this->fieldId);
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

            $this->file = $model->path.'/'.$model->slug.'.'.$model->extension;

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

    protected function cleanupOldUploads()
    {
        $storage = Storage::disk('local');

        foreach ($storage->allFiles('livewire-tmp') as $filePathname) {
            // On busy websites, this cleanup code can run in multiple threads causing part of the output
            // of allFiles() to have already been deleted by another thread.
            if (! $storage->exists($filePathname)) {
                continue;
            }

            // $yesterdaysStamp = now()->subDay(1)->timestamp;
            $yesterdaysStamp = now()->subSeconds(5)->timestamp;
            if ($yesterdaysStamp > $storage->lastModified($filePathname)) {
                $storage->delete($filePathname);
            }
        }
    }

    public function newFolder()
    {
        $new_folder = genSlug($this->foldername);
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
                'user_id' => Auth::id(),
            ]);
            $this->dispatch('resetCom');
        } else {
            $error = __('media.error_creating_dir');
        }

        $this->FormFolder = false;
    }

    public static function convert($src, $des, $quality, $speed)
    {
        if (! function_exists('imageavif') && AVIFE_IMAGICK_VER <= 0) {
            return;
        }
        if (! $src && ! $des && ! $quality && ! $speed) {
            return false;
        }

        $fileType = getimagesize($src)['mime'];
        // Try Imagick First
        if (AVIFE_IMAGICK_VER > 0) {
            $imagick = new \Imagick();
            $imagick->readImage($src);
            $imagick->setImageFormat('avif');
            if ($quality > 0) {
                $imagick->setCompressionQuality($quality);
                $imagick->setImageCompressionQuality($quality);
            } else {
                $imagick->setCompressionQuality(1);
                $imagick->setImageCompressionQuality(1);
            }

            $imagick->writeImage($des);

            return;
        }
        //Try GD
        if ($fileType == 'image/jpeg' || $fileType == 'image/jpg') {
            $sourceGDImg = @imagecreatefromjpeg($src);
        }
        if ($fileType == 'image/png') {
            $sourceGDImg = @imagecreatefrompng($src);
        }
        if ($fileType == 'image/webp') {
            $sourceGDImg = @imagecreatefromwebp($src);
        }
        if (gettype($sourceGDImg) == 'boolean') {
            return;
        }
        @imageavif($sourceGDImg, $des, $quality, $speed);

        // file_put_contents($des, "\0", FILE_APPEND);
        // Storage::path($des);

        @imagedestroy($sourceGDImg);
    }

    public function _finishUpload($name, $tmpPath, $isMultiple)
    {

        $this->cleanupOldUploads();
        $this->filesystem = config('kompass.storage.disk');

        $files = collect($tmpPath)->map(function ($i) {
            return \Livewire\Features\SupportFileUploads\TemporaryUploadedFile::createFromLivewire($i);
        })->toArray();

        $this->dispatch('upload:finished', name: $name, tmpFilenames: collect($files)->map->getFilename()->toArray())->self();

        $file = new File;

        foreach ($files as $filedata) {
            $filename = pathinfo($filedata->getClientOriginalName(), PATHINFO_FILENAME);
            $filesSlug = genSlug($filename);
            $original_ext = $filedata->getClientOriginalExtension();
            $type = $file->getType($original_ext);
            $time = date('Y_m_B');
            $details = config('kompass.media') ?? '{}';
            $timefilesSlug = $time.'_'.$filesSlug;

            $storelink = $filedata->storeAs($this->dir, $time.'_'.$filesSlug.'.'.$original_ext, $this->filesystem);

            $imageMimeTypes = [
                'image/jpeg',
                'image/png',
                'image/webp',
                'image/gif',
            ];
            if (in_array($filedata->getMimeType(), $imageMimeTypes)) {
                if ($this->dir) {
                    $des = Storage::path('public/'.$this->dir.'/'.$timefilesSlug.'.avif');
                } else {
                    $des = Storage::path('public/'.$timefilesSlug.'.avif');
                }

                self::convert(asset($storelink), $des, 60, 6);
                // foreach ($details['thumbnails'] as $thumbnail_data) {
                //     $thumbnail = $filedata->storeAs($this->dir, $time.'_'.$filesSlug.'_'.$thumbnail_data['name'].'.'.$original_ext, $this->filesystem);
                //     $this->createThumbnail($thumbnail_data['type'], $thumbnail, $thumbnail_data['width'], $thumbnail_data['height'] ?? null, $thumbnail_data['position'] ?? 'center', $thumbnail_data['quality'] ?? 60, $original_ext);
                // }
            }

            if ($storelink) {
                $file::create([
                    'path' => $this->dir,
                    'name' => $filename,
                    'slug' => $timefilesSlug,
                    'extension' => $original_ext,
                    'type' => $type,
                    'alt' => $filename,
                    'description' => '',
                    'user_id' => Auth::id(),
                ]);
            }
        }
        $this->reset('files');
        $this->mount('mediafiles');
        $this->dispatch('resetCom');
    }

    public function createThumbnail($type, $path, $width, $height, $position, $quality, $original_ext)
    {
        $content = Storage::disk($this->filesystem)->get($path);
        $image = Image::make($content);

        if ($type == 'fit') {
            Storage::disk($this->filesystem)->put($path, $image
                ->fit($width, $height, function ($constraint) {
                    $constraint->upsize();
                }, $position)
                ->encode($original_ext, ($quality ?? 90))->encoded);

            $des = Storage::path('public/'.$path.'.avif');
            self::convert(asset($path), $des, 50, 6);
        } elseif ($type == 'crop') {
            Storage::disk($this->filesystem)->put($path, $image
                ->crop($width, $height, null, null)
                ->encode($original_ext, ($quality ?? 90))->encoded);
        } elseif ($type == 'resize') {
            Storage::disk($this->filesystem)->put($path, $image
                ->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->encode($original_ext, ($quality ?? 90))->encoded);
        }
    }

    public function selectField($media_id, $fieldOrPageName)
    {

        if ($fieldOrPageName == 'setting') {
            Setting::updateOrCreate(['id' => $this->field_id], ['data' => $media_id]);
            $this->dispatch('refreshmedia');
        } else {
            Datafields::updateOrCreate(
                ['id' => $this->field_id], [
                    'data' => $media_id,
                    'type' => $fieldOrPageName,
                    'block_id' => $this->block_id]
            );
        }

        $this->dispatch('refreshmedia');
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

        if (Storage::disk('local')->exists('/public/'.$file->path.'/'.$file->slug.'.'.$file->extension)) {
            $details = config('kompass.media') ?? '{}';
            // foreach ($details['thumbnails'] as $thumbnail_data) {

            //     dump($thumbnail_data);
            //     Storage::disk('local')->delete('/public/'.$file->path.'/'.$file->slug.'_'.$thumbnail_data['name'].'.'.$file->extension);
            //     Storage::disk('local')->delete('/public/'.$file->path.'/'.$file->slug.'_'.$thumbnail_data['name'].'.avif');
            //     // if (Storage::disk('local')->exists('/public/'.$file->path.'/'.$file->slug.'_'.$thumbnail_data['name'].'.avif')) {
            //     //     Storage::disk('local')->delete('/public/'.$file->path.'/'.$file->slug.'_'.$thumbnail_data['name'].'.avif');

            //     // }
            // }
            Storage::disk('local')->delete('/public/'.$file->path.'/'.$file->slug.'.avif');
            Storage::disk('local')->delete('/public/'.$file->path.'/'.$file->slug.'.'.$file->extension);
            if (Storage::disk('local')->delete('/public/'.$file->path.'/'.$file->slug.'.'.$file->extension)) {
                File::destroy($this->iditem);
                $this->FormDelete = false;
                $this->FormEdit = false;
            }
        }
        $this->dispatch('resetCom');
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
