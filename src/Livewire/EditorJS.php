<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\TemporaryUploadedFile;
use Illuminate\Support\Facades\Storage;
use Secondnetwork\Kompass\Models\Datafield;

class EditorJS extends Component
{
    use WithFileUploads;

    public $uploads = [];

    public $editorId;

    public $data;

    public $class;

    public $style;

    public $readOnly;

    public $placeholder;

    public $uploadDisk;

    public $downloadDisk;

    public $imagesPath;

    public $logLevel;

    protected $listeners = [
        'savedatajs' => 'save',
    ];

    public function mount(
        $editorId = [],
        $value = [],
        $class = '',
        $style = '',
        $readOnly = false,
        $placeholder = null,
        $uploadDisk = null,
        $downloadDisk = null
    ) {
        if (is_null($uploadDisk)) {
            $uploadDisk = config('livewire-editorjs.default_img_upload_disk');
        }

        if (is_null($downloadDisk)) {
            $downloadDisk = config('livewire-editorjs.default_img_download_disk');
        }

        if (is_null($placeholder)) {
            $placeholder = config('livewire-editorjs.default_placeholder');
        }

        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        $this->editorId = $editorId;
        $this->data = $value;
        $this->class = $class;
        $this->style = $style;
        $this->readOnly = $readOnly;
        $this->placeholder = $placeholder;
        $this->uploadDisk = $uploadDisk;
        $this->downloadDisk = $downloadDisk;
        $this->logLevel = config('livewire-editorjs.editorjs_log_level');
    }

    public function completedImageUpload(string $uploadedFileName, string $eventName, $fileName = null)
    {
        /** @var TemporaryUploadedFile $tmpFile */
        $tmpFile = collect($this->uploads)
            ->filter(function (TemporaryUploadedFile $item) use ($uploadedFileName) {
                return $item->getFilename() === $uploadedFileName;
            })
            ->first();

        // When no file name is passed, we use the hashName of the tmp file
        $storedFileName = $tmpFile->storeAs(
            '/'.$this->imagesPath,
            $fileName ?? $tmpFile->hashName(),
            $this->uploadDisk
        );

        $this->dispatchBrowserEvent($eventName, [
            'url' => Storage::disk($this->uploadDisk)->url($storedFileName),
        ]);
    }

    public function loadImageFromUrl(string $url)
    {
        $name = basename($url);
        $content = file_get_contents($url);

        Storage::disk($this->downloadDisk)->put($name, $content);

        return Storage::disk($this->downloadDisk)->url($name);
    }

    public function save()
    {
        if (! empty($this->data)) {

            Datafield::whereId($this->editorId)->update(['data' => $this->data]);
            
        }
        // $this->dispatch('editorjssave', $this->data, $this->editorId);
    }

    #[Layout('kompass::admin.layouts.app')]
    public function render()
    {
        return view('kompass::livewire.editorjs');
    }
}
