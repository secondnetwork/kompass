<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Secondnetwork\Kompass\Models\Blocktemplates;

class BlocksTable extends Component
{
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

    public function call_emit_reset()
    {
        $this->dispatch('status');
    }

    protected function headerTable(): array
    {
        return ['', 'name', 'type', ''];
    }

    protected function dataTable(): array
    {
        return ['name', 'type'];
    }

    public function mount()
    {
        $this->headers = $this->headerTable();
        $this->data = $this->dataTable();
    }

    private function resultDate()
    {
        return Blocktemplates::where('name', 'like', '%'.$this->search.'%')
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->simplePaginate($this->perPage);
    }

    public function render()
    {
        return view('kompass::livewire.blocks.blocks-table', ['pages' => $this->resultDate()])->layout('kompass::admin.layouts.app');
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;
        if ($action == 'add') $this->FormAdd = true;
        if ($action == 'delete') $this->FormDelete = true;
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
        if (file_exists($path)) return;
        $content = "@props(['item' => ''])
        @if(\$item->type == '{$type}')
        <div>
            {{-- {$type} block content --}}
        </div>
        @endif";
        file_put_contents($path, $content);
    }

    public function updatedName($value) { $this->type = Str::slug($value); }

    public function delete()
    {
        Blocktemplates::destroy($this->selectedItem);
        $this->FormDelete = false;
    }

    public function handleSort($item, $position)
    {
        $blocks = Blocktemplates::orderBy('order', 'ASC')->get();
        $movedItemIndex = $blocks->search(fn ($block) => $block->id == $item);
        if ($movedItemIndex === false) return;
        $movedItem = $blocks->pull($movedItemIndex);
        $blocks->splice($position, 0, [$movedItem]);
        foreach ($blocks->values() as $index => $block) {
            if ($block->order !== $index) $block->update(['order' => $index]);
        }
        $this->call_emit_reset();
    }

    public function toJSON(): string { return '{}'; }
}
