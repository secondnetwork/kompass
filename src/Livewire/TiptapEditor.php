<?php

namespace Secondnetwork\Kompass\Livewire;

use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Secondnetwork\Kompass\Models\Datafield;

class TiptapEditor extends Component
{
    protected string $name = 'tiptap-editor';

    public $editorId;

    public $data;

    public $class;

    public $style;

    public $readOnly;

    public $placeholder;

    public $uploadDisk;

    public $imagesPath;

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
        $uploadDisk = null
    ) {
        if (is_null($uploadDisk)) {
            $uploadDisk = config('kompass.default_img_upload_disk');
        }

        if (is_null($placeholder)) {
            $placeholder = config('kompass.default_placeholder');
        }

        $this->editorId = $editorId;
        $this->data = $value;
        $this->class = $class;
        $this->style = $style;
        $this->readOnly = $readOnly;
        $this->placeholder = $placeholder;
        $this->uploadDisk = $uploadDisk;
    }

    public function updatedData($value)
    {
        $this->save();
    }

    public function save()
    {
        if (! empty($this->data) && $this->editorId) {
            Datafield::find($this->editorId)->update(['data' => $this->data]);
        }
    }

    public function uploadImage($file)
    {
        $this->imagesPath = config('kompass.storage.images_path', 'uploads/images');
        
        $filesystem = config('kompass.storage.disk', 'public');
        
        $path = $file->storeAs(
            '/' . $this->imagesPath,
            $file->hashName(),
            $filesystem
        );

        return [
            'url' => Storage::disk($filesystem)->url($path),
            'path' => $path
        ];
    }

    #[Layout('kompass::admin.layouts.app')]
    public function render()
    {
        return view('kompass::livewire.tiptap-editor');
    }
}
