<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Component;
use Secondnetwork\Kompass\Models\Setting;

class Settings extends Component
{
    public $search;

    protected $queryString = ['search'];

    public $orderBy = 'order';

    public $orderAsc = true;

    public $FormDelete = false;

    public $FormAdd = false;

    public $FormEdit = false;

    public $data;

    public $name;

    public $value;

    public $key;

    public $group;

    public $type;

    protected $rules = [

        'name' => '',
        'value' => '',
        'key' => '',
        'group' => '',
        'type' => '',

    ];

    protected function headerTable(): array
    {
        return [
            '',
            'Name',
            'Value',
            '',
            // 'status',
            // 'Updated',

        ];
    }

    protected function dataTable(): array
    {
        return [
            'name',
            'data',
        ];
    }

    public function mount()
    {
        $this->headers = $this->headerTable();
        $this->data = $this->dataTable();
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;

        if ($action == 'add') {
            $this->selectedItem = null;
            $this->FormAdd = true;
            $this->name = '';
            $this->key = '';
            $this->group = '';
            $this->value = '';
            $this->type = '';
        }

        if ($action == 'update') {
            $model = Setting::findOrFail($this->selectedItem);
            $this->name = $model->name;
            $this->key = $model->key;
            $this->group = $model->group;
            $this->value = $model->data;
            $this->type = $model->type;
            $this->FormAdd = true;
        }

        if ($action == 'delete') {
            $this->FormDelete = true;
        }
    }

    public function addNew()
    {
        $validate = $this->validate();

        Setting::updateOrCreate([
            'id' => $this->selectedItem,
        ],
             [
                 'name' => $this->name,
                 'data' => $this->value,
                 'key' => strtolower($this->key),
                 'group' => strtolower($this->group),
                 'type' => $this->type,
             ]);

        $this->FormAdd = false;
    }

    public function delete()
    {
        Setting::destroy($this->selectedItem);
        $this->FormDelete = false;
    }

    private function resultDate()
    {
        return Setting::where('key', 'like', '%'.$this->search.'%')
                     ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->get();
    }

    private function resultGroup()
    {
        return Setting::select('group')
        ->orderBy('group', 'desc')
        ->groupBy('group')
        ->get();
    }

    public function render()
    {
        return view('kompass::livewire.settings-dev', [
            'settings' => $this->resultDate(),
            'settingsGroup' => $this->resultGroup(),
        ])->layout('kompass::admin.layouts.app');
    }

    public function updateOrder($list)
    {
        foreach ($list as $itemg) {
            Setting::whereId($itemg['value'])->update(['order' => $itemg['order']]);
        }
    }
}
