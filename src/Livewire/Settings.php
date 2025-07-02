<?php

namespace Secondnetwork\Kompass\Livewire;

use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
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

    public $valuedata = [];

    #[Rule('required|min:3')]
    public $key;

    #[Rule('required|min:3')]
    public $group;

    public $description;

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
            'key',
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

    public function saveEditorState($editorJsonData, $id)
    {
        if (! empty($editorJsonData)) {
            Setting::whereId($id)->update(['data' => $editorJsonData]);
        }
    }

    #[on('refresh-setting')]
    public function resetView()
    {
        $this->FormMedia = false;
        $this->loadSetting();
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;

        if ($action == 'add') {
            $this->selectedItem = null;
            $this->FormAdd = true;
            $this->name = '';
            $this->group = '';
            $this->valuedata = '';
            $this->type = '';
            $this->key = '';
        }
        if ($action == 'addMedia') {
            $this->getId = $itemId;
            $this->FormMedia = true;
            $this->dispatch('getIdField_changnd', $this->getId, 'setting');
        }
        if ($action == 'update') {
            $this->loadSetting();
            $this->FormAdd = true;

        }

        if ($action == 'delete') {
            $this->FormDelete = true;
        }
    }

    public function loadSetting()
    {
        $model = Setting::findOrFail($this->selectedItem);
        $this->getId = $model->id;
        $this->name = $model->name;
        $this->group = $model->group;
        $this->valuedata = $model->data;
        $this->type = $model->type;
        $this->key = $model->key;
        // $this->description = $model->description;
    }

    public function addNew()
    {
        $validate = $this->validate();
        if ($this->valuedata == '0') {
            $this->valuedata = '';
        }
        $this->dispatch('savedatajs');

        Setting::updateOrCreate([
            'id' => $this->selectedItem,
        ],
            [
                'name' => $this->name,
                'data' => $this->valuedata,
                'key' => Str::slug($this->key, '-', 'de'),
                'group' => strtolower($this->group),
                'type' => $this->type,
            ]);

        $this->FormAdd = false;
    }

    public function update($id, $el)
    {

        Setting::whereId($id)->update(['data' => $el]);
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
        return Setting::query()->whereNot('group', 'global')->orderBy('order', 'asc')->get();
    }

    private function resultDateGlobal()
    {
        return Setting::query()->where('group', 'global')->orderBy('order', 'asc')->get();
    }

    private function resultGroup()
    {
        return Setting::query()->select('group')->whereNot('group', 'global')
            ->orderBy('group')
            ->groupBy('group')
            ->get();
    }

    public function render()
    {
        return view('kompass::livewire.settings', [
            'settingsglobal' => $this->resultDateGlobal(),
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
