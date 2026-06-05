<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Models\Redirect;

class Redirection extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    public string $orderBy = 'updated_at';

    public bool $orderAsc = false;

    public bool $FormAdd = false;

    public ?int $editId = null;

    public string $old_url = '';

    public string $new_url = '';

    public int $status_code = 301;

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return [
            'old_url' => 'required|string|max:255|unique:redirections,old_url,'.($this->editId ?? 'NULL').',id',
            'new_url' => 'required|string|max:255',
            'status_code' => 'required|in:301,302,410',
        ];
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

    public function create(): void
    {
        $this->resetValidation();
        $this->reset(['editId', 'old_url', 'new_url', 'status_code']);
        $this->status_code = 301;
        $this->FormAdd = true;
    }

    public function editItem($id): void
    {
        $redirect = Redirect::findOrFail($id);

        $this->resetValidation();
        $this->editId = $redirect->id;
        $this->old_url = $redirect->old_url;
        $this->new_url = $redirect->new_url;
        $this->status_code = (int) $redirect->status_code;
        $this->FormAdd = true;
    }

    public function save(): void
    {
        $this->validate();

        Redirect::updateOrCreate(
            ['id' => $this->editId],
            [
                'old_url' => $this->old_url,
                'new_url' => $this->new_url,
                'status_code' => $this->status_code,
            ],
        );

        $this->FormAdd = false;
        $this->reset(['editId', 'old_url', 'new_url', 'status_code']);
        $this->resetPage();
    }

    public function delete($id): void
    {
        Redirect::whereKey($id)->delete();
        $this->resetPage();
    }

    private function resultDate()
    {
        return Redirect::query()
            ->where('old_url', 'like', '%'.$this->search.'%')
            ->orWhere('new_url', 'like', '%'.$this->search.'%')
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate(20);
    }

    public function render()
    {
        return view('kompass::livewire.redirect', [
            'pages' => $this->resultDate(),
        ])->layout('kompass::admin.layouts.app');
    }
}
