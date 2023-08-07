<?php

declare(strict_types=1);

namespace Secondnetwork\Kompass\Components\RichText;

use Illuminate\Support\Js;
use Secondnetwork\Kompass\Components\BladeComponent;
use Secondnetwork\Kompass\Concerns\HandlesValidationErrors;
use Secondnetwork\Kompass\Concerns\HasModels;
use Secondnetwork\Kompass\Concerns\QuillOptions;

class Quill extends BladeComponent
{
    use HandlesValidationErrors;
    use HasModels;

    protected static array $assets = ['alpine', 'quill'];

    public function __construct(
        public ?string $name = null,
        public ?string $id = null,
        public ?string $value = null,
        bool $showErrors = true,
        public bool $autofocus = false,
        public bool $readonly = false,
        public ?string $placeholder = null,
        public ?QuillOptions $quillOptions = null,
    ) {
        $this->id = $this->id ?? $this->name;
        $this->value = $this->name ? old($this->name, $this->value) : $this->value;
        // $this->showErrors = $showErrors;

        if (is_null($this->quillOptions)) {
            $this->quillOptions = QuillOptions::defaults();
        }
    }

    public function options(): Js
    {
        return Js::from([
            'autofocus' => $this->autofocus,
            'theme' => $this->quillOptions->theme,
            'readOnly' => $this->readonly,
            'placeholder' => $this->placeholder,
            'toolbar' => $this->quillOptions->getToolbar(),
            'toolbarHandlers' => count($this->quillOptions->toolbarHandlers) ? $this->quillOptions->toolbarHandlers : null,
        ]);
    }
}
