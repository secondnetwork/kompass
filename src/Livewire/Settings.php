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
     
    public $icon;

    public $value;

    public $valuedata = [];

    #[Rule('required|min:3')]
    public $key;

    #[Rule('required|min:3')]
    public $group;

    public $description;

    public $type;

    public $navigation = [];

    protected $rules = [

        'name' => 'required',
        'value' => '',
        'key' => 'required',
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
        $this->navigation = [
            [
                'slug' => '',
                'name' => 'Theme ' . __('Settings'),
            ],
            [
                'slug' => 'page_information',
                'name' => __('Page Information'),
                'icon' => 'tabler-info-circle',
            ],
            [
                'slug' => 'page_appearance',
                'name' => __('Page Appearance'),
                'icon' => 'tabler-palette',
            ],
            [
                'slug' => '',
                'name' => __('Settings'),
            ],
            [
                'slug' => 'backend',
                'name' => 'Login ' . __('Page'),
                'icon' => 'tabler-login',
            ],
            [
                'slug' => 'admin_panel',
                'name' => __('Admin Panel'),
                'icon' => 'tabler-layout-dashboard',
            ],
            [
                'slug' => 'global',
                'name' => __('Global Settings'),
                'icon' => 'tabler-world',
            ],
            [
                'slug' => '',
                'name' => __('Tools'),
            ],
            [
                'slug' => 'activity-log',
                'name' => __('Activity-log'),
                'icon' => 'tabler-activity',
            ],
            [
                'slug' => 'error-log',
                'name' => __('Error-log'),
                'icon' => 'tabler-alert-triangle',
            ],
        ];
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

    public function updatedType($value)
    {
        if ($this->selectedItem) {
            Setting::whereId($this->selectedItem)->update(['type' => $value]);
        }
    }

    public function saveStepOne()
    {
        $validate = $this->validate([
            'name' => 'required|min:3',
            'key' => 'required|min:3',
            'group' => 'required',
        ]);

        $setting = Setting::updateOrCreate([
            'id' => $this->selectedItem,
        ],
        [
            'name' => $this->name,
            'key' => Str::slug($this->key, '-', 'de'),
            'group' => strtolower($this->group),
            'type' => $this->type ?: 'text',
        ]);

        $this->selectedItem = $setting->id;
        $this->type = $setting->type;
        $this->loadSetting();
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
