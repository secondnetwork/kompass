<?php

namespace Secondnetwork\Kompass\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;
use Secondnetwork\Kompass\Models\Setting;

class Settings extends Component
{
    public $search;

    public $headers;

    public $selectedItem;

    public $pagetap = 'admin';

    protected $queryString = ['pagetap'];

    public $orderBy = 'order';

    public $orderAsc = true;

    public $FormDelete = false;

    public $FormAdd = false;

    public $FormEdit = false;

    public $FormMedia = false;

    public $getId;

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
        'group' => 'required',
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

    protected $listeners = [
        'editorjssave' => 'saveEditorState',
    ];

    public function saveEditorState($editorJsonData, $id)
    {
        if (! empty($editorJsonData)) {
            Setting::whereId($id)->update(['data' => $editorJsonData]);
        }
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;

        if ($action == 'add') {
            $this->selectedItem = null;
            $this->FormAdd = true;
            $this->name = '';
            $this->group = '';
            $this->value = '';
            $this->type = '';
        }
        if ($action == 'addMedia') {
            $this->getId = $itemId;
            $this->FormMedia = true;
            $this->dispatch('getIdField_changnd', $this->getId, 'setting');
        }
        if ($action == 'update') {
            $model = Setting::findOrFail($this->selectedItem);
            $this->name = $model->name;
            $this->group = $model->group;
            $this->value = $model->data;
            $this->type = $model->type;
            $this->FormAdd = true;

        }

        if ($action == 'delete') {
            $this->FormDelete = true;
        }
    }

    public function pagetaps($group)
    {
        $this->reset($this->pagetap);
        $this->pagetap = $group;
        dump(Setting::where('group', $this->pagetap)->orderBy('order', 'asc')->get());

    }

    public function addNew()
    {
        $validate = $this->validate();
        if ($this->value == '0') {
            $this->value = '';
        }
        $this->dispatch('savedatajs');

        Setting::updateOrCreate([
            'id' => $this->selectedItem,
        ],
            [
                'name' => $this->name,
                'data' => $this->value,
                'key' => Str::slug($this->name, '-', 'de'),
                'group' => strtolower($this->group),
                'type' => $this->type,
            ]);

        $this->FormAdd = false;
    }

    public function removemedia($id)
    {
        Setting::whereId($id)->update(['data' => '']);
    }

    public function delete()
    {
        Setting::destroy($this->selectedItem);
        $this->FormDelete = false;
    }

    private function resultDate()
    {

        // dd(Setting::query()->orderBy('order', 'asc')->get());
        // return Setting::where('group', $this->pagetap)->orderBy('order', 'asc')->get();
        return Setting::query()->orderBy('order', 'asc')->get();
    }

    private function resultGroup()
    {
        return Setting::query()->select('group')
            ->orderBy('group')
            ->groupBy('group')
            ->get();
    }

    public function render()
    {
        return view('kompass::livewire.settings', [
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
