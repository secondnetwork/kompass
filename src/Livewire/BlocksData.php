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
        'data.slug' => '',
        'data.grid' => '',
        'data.iconclass' => '',
        'data.icon_img_path' => '',
        'fields.*.id' => '',
        'fields.*.name' => '',
        'fields.*.grid' => '',
        'fields.*.slug' => '',
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
        $this->slug = $this->data->slug;
        $this->blocktemplatesId = $id;
        // $this->fields->slug = $mfields->slug;
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

    public function saveUpdate($id)
    {
        $block = Blocktemplates::findOrFail($id);
        // $blockfields = Blockfields::whereId('blocktemplate_id',$id)->orderBy('order')->get();

        $validate = $this->validate();

        // $file = new File;
        // if (!empty($this->filestoredata)) {
        //     foreach ($this->filestoredata as $filedata) {

        //         $fliename = $filedata->getClientOriginalName();
        //         $slugname = $file->genSlug($filedata->getClientOriginalName());
        //         $original_ext = $filedata->getClientOriginalExtension();
        //         $type = $file->getType($original_ext);

        //         $original_strorlink = $filedata->store('block_icons','public');
        //         $validate['data']['icon_img_path'] = $original_strorlink;
        //     }

        // }
        if (! empty($this->filestoredata)) {
            $original_strorlink = $this->filestoredata->store('block_icons', 'public');
            $validate['data']['icon_img_path'] = $original_strorlink;
        }

        $validate['data']['slug'] = Str::slug($validate['data']['name']);

        $block->update($validate['data']);

        foreach ($validate['fields'] as $blockfields) {
            $blockfields['slug'] = Str::slug($blockfields['name']);
            Blockfields::whereId($blockfields['id'])->update($blockfields);
        }

        $this->nofifiction = true;
        $this->blockfieldsUpdate($id);

        $this->resetpage();
        // return redirect()->to('admin/blocks/show/'.$id);
        session()->flash('message', 'Post successfully updated.');
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

    public function updateOrder($list)
    {
        // dd($list);
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
