<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Models\Page;

class NewsTable extends Component
{
    use WithPagination;

    public $search;

    public $headers;

    protected $queryString = ['search'];

    public $perPage = 10;

    public $orderBy = 'order';

    public $orderAsc = true;

    protected function headerTable(): array
    {
        return [
            'thumbnails',
            'title',
            'slug',
            // 'description',
            'Updated',
            '', ];
    }

    protected function dataTable(): array
    {
        return [
            'thumbnails',
            'title',
            'slug',
            // 'meta_description',
            'updated_at', ];
    }

    public function mount()
    {
        $this->headers = $this->headerTable();
        $this->data = $this->dataTable();
    }

    private function resultDate()
    {
        return Page::where('title', 'like', '%'.$this->search.'%')
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->simplePaginate($this->perPage);
    }

    public function render()
    {
        return view('kompass::livewire.News-table', [
            'News' => $this->resultDate(),
        ]);
    }
}
