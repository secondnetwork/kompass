<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Secondnetwork\Kompass\Models\Blocktemplates;

class BlocksTable extends Component
{
    /**
     * The component's listeners.
     *
     * @var array
     */
    use WithPagination;

    public $headers;

    public $data;

    public $action;

    public $selectedItem;

    public $FormDelete = false;

    public $FormAdd = false;

    public $FormEdit = false;

    public $perPage = 10000;

    public $search = '';

    public $orderBy = 'order';

    public $orderAsc = true;

    public $blockarray;

    public $name;

    public $type;

    protected $rules = [

        'name' => 'required|string|min:1',
        'type' => 'required|string|min:1|unique:blocktemplates,type',

    ];

    protected function headerTable(): array
    {
        return [
            '',
            'name',
            'type',
            // 'thumbnails',
            // 'description',
            // 'status',
            // 'Updated',
            '',
        ];
    }

    protected function dataTable(): array
    {
        return [
            'name',
            'type',
            // 'thumbnails',
            // 'meta_description',
            // 'status',
            // 'created_at',
        ];
    }

    public function mount()
    {
        $this->headers = $this->headerTable();
        $this->data = $this->dataTable();
        // $this->form->fill();
    }

    private function resultDate()
    {
        return Blocktemplates::where('name', 'like', '%'.$this->search.'%')
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->simplePaginate($this->perPage);
    }
    // public function mount($id)
    // {
    //     $this->blockarray = Block::findOrFail($id);
    // }

    public function render()
    {
        return view('kompass::livewire.blocks.blocks-table', [
            'pages' => $this->resultDate(),
        ])
            ->layout('kompass::admin.layouts.app');

        // ->slot('main');
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;

        if ($action == 'add') {
            // This will show the modal on the frontend
            // $this->reset(['name', 'email', 'password', 'role']);
            $this->FormAdd = true;
        }
        if ($action == 'delete') {
            $this->FormDelete = true;
        }
    }

    public function saveBlock()
    {
        $validatedData = $this->validate();
        $validatedData['type'] = Str::slug($this->name);
        
        $block = Blocktemplates::create($validatedData);
        
        $this->createBlockViewFile($block->type);
        
        $this->reset(['name', 'type']);
        $this->FormAdd = false;
    }

    protected function createBlockViewFile($type)
    {
        $path = resource_path('views/components/blocks/' . $type . '.blade.php');
        
        if (file_exists($path)) {
            session()->flash('error', "File {$type}.blade.php already exists.");
            return;
        }
        
        $content = <<<BLADE
@props([
    'item' => '',
])
@if(\$item->type == '{$type}')
<div>
    {{-- {$type} block content --}}
</div>
@endif
BLADE;
        
        if (!file_put_contents($path, $content)) {
            session()->flash('error', "Could not create file {$type}.blade.php.");
        }
    }

    public function updatedName($value)
    {
        $this->type = Str::slug($value);
    }

    public function delete()
    {
        Blocktemplates::destroy($this->selectedItem);
        $this->FormDelete = false;
    }

    public function updateOrder($list)
    {
        foreach ($list as $itemg) {
            Blocktemplates::whereId($itemg['value'])->update(['order' => $itemg['order']]);
            // foreach($itemg['items'] as $item){
            //     block::whereId($item['value'])->update(['order' => $item['order']]);
            // }
        }

        // block::whereId($list['value'])->update(['order' => $list['order']]);
    }
}
