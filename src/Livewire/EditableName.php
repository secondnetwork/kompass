<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Component;
use Secondnetwork\Kompass\Models\Block;

class EditableName extends Component
{
    public Block $itemblocks;

    public $isEditing = false;

    public $newName;

    public $size = 'sm';

    public function mount(Block $itemblocks, $size = 'sm')
    {
        $this->itemblocks = $itemblocks;
        $this->newName = $itemblocks->name;
        $this->size = $size;
    }

    public function toggleEditingState()
    {
        $this->isEditing = ! $this->isEditing;
        $this->dispatch('focus-input');
        
    }

    public function savename()
    {
        if ($this->newName) {
            Block::whereId($this->itemblocks->id)->update(['name' => $this->newName]);
        }

        $this->isEditing = false;
        $this->dispatch('reload-pages-data');
    }

    public function render()
    {
        return view('kompass::livewire.editable-name');
    }
}
