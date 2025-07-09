<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Secondnetwork\Kompass\Models\Blockfields;

class FieldEditor extends Component
{

    public Blockfields $field;


    public $name;
    public $grid;
    public $type;
    public $selectedItem;
    public $FormDelete = false;


    protected $rules = [

        'name' => 'nullable|string|max:255',
        'grid' => 'required|string',
        'type' => '',
    ];

    public function mount($fieldId)
    {

        $this->field = Blockfields::findOrFail($fieldId);

        $this->name = $this->field->name;
        $this->grid = $this->field->grid;
        $this->type = $this->field->type;
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;

        if ($action == 'delete') {
            $this->FormDelete = true;
        }
    }

    /**
     * Speichert die Änderungen für dieses eine Feld.
     * Benötigt KEINEN $id Parameter.
     */
    #[On('field-update')]
    public function saveField() // Umbenannt von Upsave, kein Parameter
    {

        Blockfields::whereId($this->field->id)->update([
            'name' => $this->name,
            'grid' => $this->grid,
            'type' => $this->type,

        ]);

        session()->flash('message-field-' . $this->field->id, 'Feld gespeichert.');
        $this->dispatch('block-resetpage');
    }

    public function delete()
    {
        Blockfields::destroy($this->field->id);
        $this->dispatch('block-resetpage');
    }

    public function render()
    {
        return view('kompass::livewire.field-editor');
    }
}
