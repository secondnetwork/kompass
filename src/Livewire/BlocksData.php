<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Secondnetwork\Kompass\Models\File;
use Illuminate\Support\Facades\Storage;
use Secondnetwork\Kompass\Models\Block;
use Secondnetwork\Kompass\Models\Blockfields;
use Secondnetwork\Kompass\Models\Blocktemplates;

class BlocksData extends Component
{
    public function call_emit_reset()
    {
        $this->mount($this->blocktemplatesId);
        $this->dispatch('status');
    }

    public function handleSort($item, $position)
    {
        $fields = Blockfields::where('blocktemplate_id', $this->blocktemplatesId)->orderBy('order', 'asc')->get();

        $movedItemIndex = $fields->search(function ($field) use ($item) {
            return $field->id == $item;
        });

        if ($movedItemIndex === false) {
            return;
        }

        $movedItem = $fields->pull($movedItemIndex);

        $fields->splice($position, 0, [$movedItem]);

        foreach ($fields->values() as $index => $field) {
            if ($field->order !== $index) {
                $field->update(['order' => $index]);
            }
        }
        $this->call_emit_reset();
    }
    use WithFileUploads;

    /**
     * The component's listeners.
     */
    public $fields;

    public $title;

    public $name;

    public $type;

    public $iconclass;

    public $grid;

    public $slug;

    public $icon_img_path;

    public $blocktemplatesId;

    public $FormBlocks = false;

    public $FormDelete = false;

    public $nofifiction = false;

    public $filestoredata;

    protected $rules = [
        'name' => 'required|string|min:1',
        'type' => 'required|string|min:1|unique:blocktemplates,type,' . null . ',id',
        'iconclass' => 'nullable',
        'grid' => 'nullable',
        'icon_img_path' => 'nullable',
    ];

    public function mount($id)
    {
        $block = Blocktemplates::findOrFail($id);
        $fields = Blockfields::where('blocktemplate_id', $id)->orderBy('order')->get();

        $this->fields = $fields;
        $this->name = $block->name;
        $this->type = $block->type;
        $this->iconclass = $block->iconclass;
        $this->grid = $block->grid;
        $this->icon_img_path = $block->icon_img_path;
        $this->blocktemplatesId = $id;
    }

    protected $listeners = ['selectItemForAction']; // Event-Name muss passen

    public function selectItemForAction($eventPayload)
    {
        $itemId = $eventPayload['itemId'];
        $action = $eventPayload['action'];
        // Rufe deine bestehende Methode auf oder handle direkt
        $this->selectItem($itemId, $action);
    }

    public function render()
    {
        return view('kompass::livewire.blocks.blocks-show')
            ->layout('kompass::admin.layouts.app');

        // ->slot('main');
    }
    #[On('block-resetpage')]
    public function resetpage()
    {
 
        $this->reset('filestoredata');
        $this->mount($this->blocktemplatesId);
        $this->dispatch('status');
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;
        if ($action == 'addblock') {
            $this->FormBlocks = true;
        }
        if ($action == 'update') {
        }

        if ($action == 'delete') {
            $this->FormDelete = true;
        }
    }

    public function delete() //delete block
    {
        Blockfields::where('id', $this->selectedItem)->delete();

        $this->FormDelete = false;
        // $this->mount($this->selectedItem);
        $this->resetpage();
    }

    public function blockfieldsUpdate($BlockfieldsId)
    {
        $this->fields = Blockfields::where('blocktemplate_id', $BlockfieldsId)->orderBy('order')->get();
    }

    public function addNewField($id)
    {
        Blockfields::Create([
            'name' => '',
            'blocktemplate_id' => $id,
            'order' => '999',
        ]
        );

        $this->blockfieldsUpdate($id);
    }

    public function saveUpdate()
    {
        $validatedData = $this->validate();
        
        $this->dispatch('field-update');
        $id = $this->blocktemplatesId;

        if (!$id) {
            session()->flash('error', 'Block Template ID nicht gefunden.');
            return;
        }

        $block = Blocktemplates::findOrFail($id);

        $dataToUpdate = [];

        if (!empty($validatedData['name'])) {
            $dataToUpdate['name'] = $validatedData['name'];
        }
        if (isset($validatedData['type'])) {
            $dataToUpdate['type'] = $validatedData['type'];
        }
        if (isset($validatedData['iconclass'])) {
            $dataToUpdate['iconclass'] = $validatedData['iconclass'];
        }
        if (isset($validatedData['grid'])) {
            $dataToUpdate['grid'] = $validatedData['grid'];
        }
        if (isset($validatedData['icon_img_path'])) {
            $dataToUpdate['icon_img_path'] = $validatedData['icon_img_path'];
        }

        if (! empty($this->filestoredata)) {
            if ($block->icon_img_path && Storage::disk('public')->exists($block->icon_img_path)) {
                Storage::disk('public')->delete($block->icon_img_path);
            }
            $original_strorlink = $this->filestoredata->store('block_icons', 'public');
            $dataToUpdate['icon_img_path'] = $original_strorlink;
            $this->filestoredata = null;
        }

        if (!empty($dataToUpdate)) {
            $block->update($dataToUpdate);
            $this->name = $block->fresh()->name;
            $this->type = $block->fresh()->type;
            $this->iconclass = $block->fresh()->iconclass;
            $this->grid = $block->fresh()->grid;
            $this->icon_img_path = $block->fresh()->icon_img_path;
        }

        $this->nofifiction = true;
        $this->resetpage();
        session()->flash('message', 'Block erfolgreich aktualisiert.');
    }

    protected function cleanupOldUploads()
    {
        $storage = Storage::disk('local');

        foreach ($storage->allFiles('livewire-tmp') as $filePathname) {
            // On busy websites, this cleanup code can run in multiple threads causing part of the output
            // of allFiles() to have already been deleted by another thread.
            if (! $storage->exists($filePathname)) {
                continue;
            }

            $yesterdaysStamp = now()->subSeconds(5)->timestamp;
            if ($yesterdaysStamp > $storage->lastModified($filePathname)) {
                $storage->delete($filePathname);
            }
        }
    }

    public function removemedia($id)
    {
        $Blocktemplates = Blocktemplates::findOrFail($id);
        $Blocktemplates->update(['icon_img_path' => null]);
        $this->resetpage();
    }

    public function updateGrid($id, $grid)
    {
        $setblock = Blocktemplates::findOrFail($id);
        $setblock->update(['grid' => $grid]);
        $this->resetpage();
    }

    public function updateOrder($list)
    {

        foreach ($list as $item) {
            blockfields::whereId($item['value'])->update(['order' => $item['order']]);
            // foreach($itemg['items'] as $item){
            //     block::whereId($item['value'])->update(['order' => $item['order']]);
            // }
        }

        $this->blockfieldsUpdate($this->blocktemplatesId);
        session()->flash('message', 'ORRRDERRR.');
        // $this->nofifiction = true;
        // $this->reset($this->data->id);
        // block::whereId($list['value'])->update(['order' => $list['order']]);
    }
}
