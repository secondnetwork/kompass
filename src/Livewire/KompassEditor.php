<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Component;
use Secondnetwork\Kompass\Helpers\EditorMigrationHelper;
use Secondnetwork\Kompass\Models\Datafield;

/**
 * Notion-style block editor — replaces Editor.js.
 *
 * Two integration modes:
 *
 *  A) Datafield drop-in (primary):
 *     <livewire:kompass-editor
 *         :editor-id="$datafield->id"
 *         :value="$datafield->data"
 *         :placeholder="__('write something...')"
 *     />
 *     Parent dispatches the `savedatajs` event to trigger persistence to
 *     datafields.data (json_decode of $value, written through the array cast).
 *
 *  B) wire:model (secondary):
 *     <livewire:kompass-editor wire:model.live="content" />
 *     Parent receives the compiled JSON string in its bound property.
 */
class KompassEditor extends Component
{
    /**
     * Compiled JSON string. Untyped because Livewire auto-assigns matching
     * props from `<livewire:kompass-editor :value="$row->data" />` BEFORE
     * mount() runs, and the source value may be an array (cast columns) or a
     * JSON string. mount() normalizes it to the canonical compiled string.
     *
     * @var string
     */
    #[Modelable]
    public $value = '';

    /** @var array<int, array{id:string,type:string,content:string}> */
    public array $blocks = [];

    public string $placeholder = '';

    public ?int $editorId = null;

    public bool $readOnly = false;

    public function mount(
        $editorId = null,
        $value = '',
        ?string $placeholder = null,
        bool $readOnly = false,
    ): void {
        $this->editorId = $editorId !== null && $editorId !== '' ? (int) $editorId : null;
        $this->readOnly = $readOnly;
        $this->placeholder = $placeholder ?? __('Tippe \'/\' für Befehle...');
        $this->blocks = EditorMigrationHelper::toFlatBlocks($value);
        $this->value = $this->compileValue($this->blocks);
    }

    public function updatedBlocks(): void
    {
        $this->value = $this->compileValue($this->blocks);
    }

    #[On('savedatajs')]
    public function save(): void
    {
        if ($this->readOnly || $this->editorId === null) {
            return;
        }

        Datafield::find($this->editorId)?->update([
            'data' => json_decode($this->value, true),
        ]);
    }

    public function render()
    {
        return view('kompass::livewire.kompass-editor');
    }

    /**
     * PHP port of the Alpine `compiledJson` getter — delegates to the helper
     * so the same consolidation logic is reused by the frontend renderer.
     *
     * @param  array<int, array{id?:string,type?:string,content?:string}>  $blocks
     */
    protected function compileValue(array $blocks): string
    {
        return EditorMigrationHelper::compileJson($blocks);
    }
}
