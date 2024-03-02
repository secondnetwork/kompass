<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Secondnetwork\Kompass\Models\Datafield;

class DatafieldItem extends Component
{

  public Datafield $datafield;

  public $data;

  public function mount()
  {
      $this->fill(
          $this->datafield->only('data')
      );
  }

  #[on('saveTheDatafield')]
  public function saveTheDatafield()
  {

    Datafield::whereId($this->datafield->id)->update(['data' => $this->data]);

  }

  #[on('editorjssave')]
  public function saveEditorState($editorJsonData, $id)
  {
dd($editorJsonData);
      if (! empty($editorJsonData)) {

          Datafield::whereId($id)->update(['data' => $editorJsonData]);
          
      }

      $this->resetPageComponent();
  }

  #[Layout('kompass::admin.layouts.app')]
  public function render()
  {
      return view('kompass::livewire.datafield-item');
  }
}