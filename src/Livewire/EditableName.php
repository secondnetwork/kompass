<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Component;
use Secondnetwork\Kompass\Models\Block;

class EditableName extends Component
{
    public Block $itemblocks;

    public $isEditing = false;

    public $newName;

    public function mount(Block $itemblocks)
    {
        $this->itemblocks = $itemblocks;
        $this->newName = $itemblocks->name;
    }

     public function toggleEditingState()
     {
         $this->isEditing = !$this->isEditing;
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