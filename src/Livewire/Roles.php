<?php

namespace Secondnetwork\Kompass\Livewire;

use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Secondnetwork\Kompass\Models\Permission;
use Secondnetwork\Kompass\Models\Role;

class Roles extends Component
{
    public $headers;

    public $action;

    public $selectedItem;

    public $FormDelete = false;

    public $FormAdd = false;

    public $FormEdit = false;

    public $FormPermissions = false;

    public $FormManagePermissions = false;

    #[Url]
    public $search;

    public $orderBy = 'name';

    public $orderAsc = true;

    #[Validate('required|regex:/^[\pL\s\-]+$/u|min:3|max:255')]
    public $name;

    #[Validate('')]
    public $display_name;

    public $guard_name;

    public $role;

    public $Roles;

    /**
     * Name of the role currently being edited in the permissions panel.
     */
    public $permissionRoleName = '';

    /**
     * Permission names currently assigned to the selected role.
     *
     * @var array<int, string>
     */
    public array $selectedPermissions = [];

    public $newPermissionName = '';

    private function headerConfig(): array
    {
        return [
            'name' => __('Role'),
            'permissions' => __('Permissions'),
            'edit' => '',
        ];
    }

    public function mount(): void
    {
        $this->headers = $this->headerConfig();

        $this->Roles = Role::orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->get();
    }

    private function resultDate()
    {
        return Role::withCount('permissions')
            ->where('name', 'like', '%'.$this->search.'%')
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
    }

    public function sortBy($field): void
    {
        if ($this->orderBy === $field) {
            $this->orderAsc = ! $this->orderAsc;
        } else {
            $this->orderBy = $field;
            $this->orderAsc = true;
        }
    }

    public function selectItem($itemId, $action): void
    {
        $this->selectedItem = $itemId;

        if ($action == 'add') {
            $this->reset(['name', 'display_name']);
            $this->FormEdit = true;
        }

        if ($action == 'update') {
            $model = Role::findOrFail($this->selectedItem);
            $this->name = $model->name;
            $this->display_name = $model->display_name;
            $this->FormEdit = true;
        }

        if ($action == 'delete') {
            $this->FormDelete = true;
        }

        if ($action == 'permissions') {
            $model = Role::findOrFail($this->selectedItem);
            $this->permissionRoleName = $model->display_name ?: $model->name;
            $this->selectedPermissions = $model->permissions->pluck('name')->all();
            $this->FormPermissions = true;
        }
    }

    public function createOrUpdateRole(): void
    {
        $role = Role::find($this->selectedItem);

        if ($role) {
            $validate = $this->validate();
            $role->update($validate);

            $this->reset(['name', 'display_name']);
            $this->FormEdit = false;
        } else {
            $validate = $this->validate();

            Role::firstOrCreate(
                ['name' => $validate['name'], 'guard_name' => 'web'],
                ['display_name' => $validate['display_name'] ?? $validate['name']],
            );

            $this->FormEdit = false;
        }

        $this->mount();
    }

    public function delete(): void
    {
        Role::destroy($this->selectedItem);
        $this->FormDelete = false;

        $this->mount();
    }

    /**
     * Open the global permission manager (create / delete permissions).
     */
    public function openPermissionManager(): void
    {
        $this->reset(['newPermissionName']);
        $this->FormManagePermissions = true;
    }

    public function createPermission(): void
    {
        $this->validate(['newPermissionName' => 'required|string|min:2|max:255']);

        Permission::firstOrCreate([
            'name' => trim($this->newPermissionName),
            'guard_name' => 'web',
        ]);

        $this->reset(['newPermissionName']);
        $this->dispatch('status');
    }

    public function deletePermission($permissionId): void
    {
        Permission::whereKey($permissionId)->delete();
        $this->dispatch('status');
    }

    /**
     * Persist the permission selection for the active role.
     */
    public function savePermissions(): void
    {
        $role = Role::findOrFail($this->selectedItem);
        $role->syncPermissions($this->selectedPermissions);

        $this->FormPermissions = false;
        $this->dispatch('status');
    }

    public function render()
    {
        $permissions = Permission::orderBy('name')->get();

        $permissionGroups = $permissions->groupBy(function ($permission) {
            if (Str::contains($permission->name, '.')) {
                return Str::before($permission->name, '.');
            }

            if (Str::contains($permission->name, ' ')) {
                return Str::after($permission->name, ' ');
            }

            return __('General');
        })->sortKeys();

        return view('kompass::livewire.roles', [
            'roles' => $this->resultDate()->get(),
            'permissions' => $permissions,
            'permissionGroups' => $permissionGroups,
        ])->layout('kompass::admin.layouts.app');
    }
}
