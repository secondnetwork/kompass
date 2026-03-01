<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Component;
use Secondnetwork\Kompass\Models\Redirect;

class Redirection extends Component
{
    public $search = '';

    public $data;

    public $headers;

    protected $queryString = ['search'];

    public $orderBy = 'updated_at';

    public $orderAsc = false;

    public $selectedItem;

    public $FormDelete = false;

    public $FormAdd = false;

    protected function headerTable(): array
    {
        return [
            'old url',
            'new url',
            'status code',
            'Updated',
            '',
        ];
    }

    protected function dataTable(): array
    {
        return [
            'old_url',
            'new_url',
            'status_code',
            'updated_at',
        ];
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;
        if ($action == 'add') {
            // This will show the modal on the frontend
            // $this->reset(['name', 'email', 'password', 'role']);
            $this->FormAdd = true;
        }
        if ($action == 'update') {
        }

        if ($action == 'delete') {
            $this->FormDelete = true;
        }
    }

    public function mount()
    {
        $this->headers = $this->headerTable();
        $this->data = $this->dataTable();
        // $this->form->fill();
    }

    // public function selectItem($itemId, $action)
    // {
    //     $this->selectedItem = $itemId;
    //     if ($action == 'add') {
    //         // This will show the modal on the frontend
    //         // $this->reset(['name', 'email', 'password', 'role']);
    //         $this->FormAdd = true;
    //     }
    //     if ($action == 'update') {
    //     }

    //     if ($action == 'delete') {
    //         $this->FormDelete = true;
    //     }
    // }

    private function resultDate()
    {
        return Redirect::where('old_url', 'like', '%'.$this->search.'%')
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate(100);
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

    public function render()
    {

        return view('kompass::livewire.redirect', [
            'pages' => $this->resultDate(),
        ])->layout('kompass::admin.layouts.app');
    }
}
