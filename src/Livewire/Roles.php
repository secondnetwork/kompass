<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Component;
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

    public $name;

    public $role;

    public $Roles;

    protected $rules = [
        'name' => 'required|regex:/^[\pL\s\-]+$/u|min:3|max:255',
    ];

    private function headerConfig()
    {
        return [
            // 'id' => '#',
            'name' => 'Role',
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
            $this->reset(['name']);
            $this->FormAdd = true;
        }

        if ($action == 'update') {
            $model = Role::findOrFail($this->selectedItem);

            $this->name = $model->name;

            $this->FormEdit = true;
        }

        if ($action == 'delete') {
            // This will show the modal on the frontend
            $this->FormDelete = true;
            // $this->dispatchBrowserEvent('openDeleteModal');
        }
    }

    public function addNew()
    {
        $validate = $this->validate();

        Role::create($validate);
        $this->FormAdd = false;
    }

    public function update()
    {
        $role = Role::findOrFail($this->selectedItem);

        $validate = $this->validate();

        $role->update($validate);
        // $user->roles()->sync($validateData['role']);
        // User::updateOrCreate()
        $this->FormEdit = false;
        session()->flash('message', 'Post successfully updated.');
    }

    public function delete()
    {
        Role::destroy($this->selectedItem);
        $this->FormDelete = false;
    }

    public function render()
    {
        return view('kompass::livewire.roles', [
            'roles' => $this->resultDate(),

        ])->layout('kompass::admin.layouts.app');
    }
}
