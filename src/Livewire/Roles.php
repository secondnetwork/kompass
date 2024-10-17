<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Secondnetwork\Kompass\Models\Role;

class Roles extends Component
{
    // use WithPagination;
    public $headers;

    public $action;

    public $selectedItem;

    public $FormDelete = false;

    public $FormAdd = false;

    public $FormEdit = false;

    public $search;

    protected $queryString = ['search'];

    #[Validate('required|regex:/^[\pL\s\-]+$/u|min:3|max:255')]
    public $name;

    #[Validate('')]
    public $display_name;
    public $guard_name;
    public $role;

    public $Roles;

    // protected $rules = [
    //     'name' => 'required|regex:/^[\pL\s\-]+$/u|min:3|max:255',
    // ];

    private function headerConfig()
    {
        return [
            // 'id' => '#',
            'name' => __('Role'),
            'edit' => '',
        ];
    }

    public function mount()
    {
        $this->headers = $this->headerConfig();

        $this->Roles = Role::all();
    }

    private function resultDate()
    {
        return Role::where('name', 'like', '%'.$this->search.'%');
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;

        if ($action == 'add') {
            // This will show the modal on the frontend
            $this->reset(['name','display_name']);
            $this->FormEdit = true;
        }

        if ($action == 'update') {
            $model = Role::findOrFail($this->selectedItem);
            $this->name = $model->name;
            $this->display_name = $model->display_name;
            $this->FormEdit = true;
        }

        if ($action == 'delete') {
            // This will show the modal on the frontend
            $this->FormDelete = true;
            // $this->dispatchBrowserEvent('openDeleteModal');
        }
    }

    public function createOrUpdateRole()
    {
        $role = Role::find($this->selectedItem);

        if ($role) {
            $validate = $this->validate();
            $role->update($validate);

            $this->reset(['name','display_name']);
            $this->FormEdit = false;
        }else{
            $validate = $this->validate();
            $array2=['guard_name' => 'web'];
            $result = array_merge($validate, $array2);
  
            Role::create($result);
            $this->FormEdit = false;
        }

        $this->mount();

    }

    public function delete()
    {
        Role::destroy($this->selectedItem);
        $this->FormDelete = false;

        $this->mount();
    }

    public function render()
    {
        return view('kompass::livewire.roles', [
            'roles' => $this->Roles,

        ])->layout('kompass::admin.layouts.app');
    }
}
