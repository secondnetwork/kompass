<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Component;
use Kolossal\Multiplex\Meta;
use Secondnetwork\Kompass\Models\Block;


class EditableMeta extends Component
{
  public $label;
  public $metaKey;
  public $itemblocks; // Gehe davon aus, dass dies ein Model ist, das die Meta-Daten hält
  public $wireAction;
  public $newName; // Für die Eingabe des neuen Wertes
  
  public $cssClasses;
  public $idAnchor;


  public function mount($label, $metaKey, $itemblocks, $wireAction)
  {
    $this->label = $label;
    $this->metaKey = $metaKey;
    $this->itemblocks = $itemblocks;
    $this->wireAction = $wireAction;
    $this->newName = $this->itemblocks->getMeta($this->metaKey);
    $this->cssClasses = Meta::published()
    ->where('key', 'css-classname')
    ->get()
    ->pluck('value')
    ->unique()
    ->map(function ($className) {
        return ['name' => $className, 'id' => $className];
    })
    ->sort(function ($a, $b) {
      return strcmp($a['name'], $b['name']);
    })
     ->values()
    ->toArray();
    
    $this->idAnchor = Meta::published()
    ->where('key', 'id-anchor')
    ->get()
    ->pluck('value')
    ->unique()
    ->map(function ($idAnchor) {
        return ['name' => $idAnchor, 'id' => $idAnchor];
    })
    ->sort(function ($a, $b) {
      return strcmp($a['name'], $b['name']);
    })
     ->values()
    ->toArray();
  }

  public function updateMeta($id, $newValue)
  {
    $this->itemblocks->deleteMeta($this->metaKey);
    $this->itemblocks->setMeta($this->metaKey, $newValue);
    $this->itemblocks->save();
    // $this->newName = $newValue;
    $this->dispatch('component:refresh');
  }


  public function render()
  {
    return view('kompass::livewire.editable-meta');
  }
}
