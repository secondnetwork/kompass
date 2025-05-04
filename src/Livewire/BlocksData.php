<?php

namespace Secondnetwork\Kompass\Livewire;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Secondnetwork\Kompass\Models\Block;
use Secondnetwork\Kompass\Models\Blockfields;
use Secondnetwork\Kompass\Models\Blocktemplates;
use Secondnetwork\Kompass\Models\File;

class BlocksData extends Component
{
    use WithFileUploads;

    /**
     * The component's listeners.
     *
     * @var array
     */
    public $data;

    public $fields;

    public $title;

    public $name;

    public $slug;

    public $icon_img_path;

    public $blocktemplatesId;

    public $FormBlocks = false;

    public $FormDelete = false;

    public $nofifiction = false;

    public $filestoredata;

    protected $rules = [
        'data.name' => 'required|string|min:3',
        // 'data.slug' => '',
        'data.grid' => '',
        'data.iconclass' => '',
        'data.icon_img_path' => '',
        'fields.*.id' => '',
        'fields.*.name' => '',
        'fields.*.grid' => '',
        // 'fields.*.slug' => '',
        'fields.*.type' => '',
        // 'filestoredata.*' => 'required|file|mimes:' . File::getAllExtensions() . '|max:' . File::getMaxSize(),
        // 'filestoredata' => 'image|max:1024', // 1MB Max
    ];

    public function mount($id)
    {
        $this->data = Blocktemplates::findOrFail($id);
        $fields = Blockfields::where('blocktemplate_id', $id)->orderBy('order')->get();

        $this->fields = $fields;
        $this->name = $this->data->name;
        // $this->slug = $this->data->slug;
        $this->blocktemplatesId = $id;
        // $this->fields->slug = $mfields->slug;
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
        $id = $this->blocktemplatesId;

        if (!$id) {
            session()->flash('error', 'Block Template ID nicht gefunden.');
            return;
        }

        // Ensure data is loaded if needed (might be redundant if always loaded in mount)
        if (empty($this->data)) {
             $this->data = Blocktemplates::findOrFail($id)->toArray();
        }
        if (empty($this->fields)) {
            $this->fields = Blockfields::where('blocktemplate_id', $id)->orderBy('order')->get()->toArray();
        }

        $validatedData = $this->validate();

        $block = Blocktemplates::findOrFail($id);

        $dataToUpdate = $validatedData['data'] ?? [];

        if (! empty($this->filestoredata)) {
            // Consider deleting the old file if it exists
            if ($block->icon_img_path && Storage::disk('public')->exists($block->icon_img_path)) {
                Storage::disk('public')->delete($block->icon_img_path);
            }
            $original_strorlink = $this->filestoredata->store('block_icons', 'public');
            $dataToUpdate['icon_img_path'] = $original_strorlink;
            $this->filestoredata = null; // Clear the temporary file upload state
        }

        if (!empty($dataToUpdate)) {
            $block->update($dataToUpdate);
        }


        if (!empty($validatedData['fields'])) {
             // Fetch all current field IDs for this template
             $currentFields = Blockfields::where('blocktemplate_id', $id)->pluck('id')->toArray();
             $validatedFieldIds = array_column($validatedData['fields'], 'id');

            foreach ($validatedData['fields'] as $key => $blockfieldsData) {
                if (isset($blockfieldsData['id']) && in_array($blockfieldsData['id'], $currentFields)) {
                    Blockfields::whereId($blockfieldsData['id'])->update([
                        'name' => $blockfieldsData['name'] ?? null,
                        'grid' => $blockfieldsData['grid'] ?? null,
                        'type' => $blockfieldsData['type'] ?? null,
                    ]);
                } else {
                     Log::error('Blockfield ID missing or invalid in validated data for key: ' . $key . ' with ID: ' . ($blockfieldsData['id'] ?? 'null'));
                }
                 // Remove processed IDs from the list to find deleted ones later (optional)
                // if (($idx = array_search($blockfieldsData['id'], $validatedFieldIds)) !== false) {
                //     unset($validatedFieldIds[$idx]);
                // }
            }
            // Optional: Handle fields that were present initially but not in the validated data (deleted)
            // $fieldsToDelete = array_diff($currentFields, array_column($validatedData['fields'], 'id'));
            // if (!empty($fieldsToDelete)) {
            //     Blockfields::whereIn('id', $fieldsToDelete)->delete();
            // }
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
