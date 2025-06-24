<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Secondnetwork\Kompass\Models\Blockfields;

class FieldEditor extends Component
{
    // Das Model wird übergeben und enthält die ID
    public Blockfields $field;

    // Öffentliche Properties für die Bindung mit wire:model
    public $name;
    public $grid;
    public $type;

    // Regeln für die öffentlichen Properties, die gespeichert werden sollen
    protected $rules = [
        // Passe die Regeln an deine Anforderungen an
        'name' => 'nullable|string|max:255', // Beispiel: Name darf leer sein
        'grid' => 'required|string',        // Beispiel: Grid ist erforderlich
        'type' => '',        // Beispiel: Typ ist erforderlich
    ];

    // Optional: Für Live-Validierung
    // public function updated($propertyName)
    // {
    //     $this->validateOnly($propertyName);
    // }

    public function mount($fieldId)
    {
        // Lade das Model
        $this->field = Blockfields::findOrFail($fieldId);

        // Initialisiere die öffentlichen Properties mit den Model-Daten
        $this->name = $this->field->name;
        $this->grid = $this->field->grid;
        $this->type = $this->field->type;
    }

    /**
     * Speichert die Änderungen für dieses eine Feld.
     * Benötigt KEINEN $id Parameter.
     */
    #[On('field-update')]
    public function saveField() // Umbenannt von Upsave, kein Parameter
    {
        // 1. Validiere die öffentlichen Properties ($name, $grid, $type)
        // $validatedData = $this->validate(); // Verwendet die $rules oben

        // 2. Update das Model in der Datenbank
        //    Verwende die validierten Daten. Keys müssen DB-Spaltennamen sein.
        Blockfields::whereId($this->field->id)->update([
            'name' => $this->name, // Oder: $validatedData['name']
            'grid' => $this->grid, // Oder: $validatedData['grid']
            'type' => $this->type, // Oder: $validatedData['type']
            // Füge hier weitere Felder hinzu, falls nötig
        ]);

        // Alternativ (oft bevorzugt, wenn Model schon geladen ist):
        /*
        $this->field->name = $this->name; // Oder: $validatedData['name']
        $this->field->grid = $this->grid; // Oder: $validatedData['grid']
        $this->field->type = $this->type; // Oder: $validatedData['type']
        $this->field->save();
        */

        // 3. Gib Feedback (optional)
        session()->flash('message-field-'.$this->field->id, 'Feld gespeichert.'); // Eindeutige Nachricht pro Feld
        $this->dispatch('block-resetpage');
        // Optional: Event an Parent senden, falls nötig
        // $this->dispatch('fieldSaved', $this->field->id)->to(BlocksData::class);
    }
    
    public function render()
    {
        return view('kompass::livewire.field-editor');
    }
}