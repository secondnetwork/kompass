<?php

namespace Secondnetwork\Kompass\Livewire;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
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

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:1',
            'type' => [
                'required',
                'string',
                'min:1',
                'unique:blocktemplates,type',
                Rule::notIn(array_keys(config('kompass.block_types', []))),
            ],
        ];
    }

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

    protected function createBlockViewFile(string $type): void
    {
        $path = resource_path('views/components/blocks/'.$type.'.blade.php');

        if (File::exists($path)) {
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $stub = <<<BLADE
        @props(['item' => ''])

        <div>
            <x-kompass::blocks-datafield :itemblocks="\$item" />
        </div>
        BLADE;

        try {
            File::put($path, $stub);
        } catch (\Throwable $e) {
            Log::error('Kompass: could not create block view file', ['type' => $type, 'path' => $path, 'error' => $e->getMessage()]);
            $this->addError('type', __('Could not create block view file. Check storage permissions.'));
        }
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

    public function sortBy($field)
    {
        if ($this->orderBy === $field) {
            $this->orderAsc = !$this->orderAsc;
        } else {
            $this->orderBy = $field;
            $this->orderAsc = true;
        }
    }

    public function toJSON(): string { return '{}'; }
}
