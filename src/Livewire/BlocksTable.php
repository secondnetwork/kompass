<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
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

        'name' => '',        'type' => '',
        // 'blockarray.meta_description' => '',
        // 'blockarray.slug' => ''

    ];

    protected function headerTable(): array
    {
        return [
            '',
            'name',
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
        $validate = $this->validate();
        Blocktemplates::create($validate);
        // $user->roles()->sync($maildata['role']);

        // $this->FormAdd = false;
        // $this->reset(['name', 'email', 'password', 'role']);
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
