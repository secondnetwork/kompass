<?php

namespace Secondnetwork\Kompass\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Models\Block;
use Secondnetwork\Kompass\Models\Page;

class PagesTable extends Component
{
    use WithPagination;

    public $search;
    protected $queryString = ['search'];
    public $perPage = 1000;
    public $orderBy = 'order';
    public $orderAsc = true;
    public $tasks;
    public $data;
    public $title;
    public $headers;
    public $meta_description;
    public $datafield = [];

    #[Locked]
    public $selectedItem;

    public $timestamps = false;
    public $FormDelete = false;
    public $FormAdd = false;
    public $FormEdit = false;

    protected $rules = [
        'title' => 'unique:pages|required|string|min:3',
        'meta_description' => '',
    ];

    public function call_emit_reset()
    {
        $this->dispatch('status');
    }

    protected function headerTable(): array
    {
        return ['', 'title', 'slug', 'status', 'Updated', ''];
    }

    protected function dataTable(): array
    {
        return ['title', 'slug', 'status', 'updated_at'];
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

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;
        if ($action == 'add') $this->FormAdd = true;
        if ($action == 'delete') $this->FormDelete = true;
    }

    public function status($id, $status)
    {
        if ($status == 'draft') Page::where('id', $id)->update(['status' => 'draft']);
        if ($status == 'published') Page::where('id', $id)->update(['status' => 'published']);
    }

    public function delete()
    {
        Page::find($this->selectedItem)->delete();
        $this->FormDelete = false;
    }

    public function addPage()
    {
        $this->validate();
        $slugNameURL = Str::slug($this->title, '-', 'de');
        $placeObj = new Page;
        if ($placeObj->whereSlug($slugNameURL)->exists()) {
            $numericalPrefix = 1;
            while (1) {
                $newSlug = $slugNameURL.'-'.$numericalPrefix++;
                $newSlug = Str::slug($newSlug, '-', 'de');
                if (!$placeObj->whereSlug($newSlug)->exists()) {
                    $newpageslug = $newSlug;
                    break;
                }
            }
        } else {
            $newpageslug = $slugNameURL;
        }

        $page = Page::create(['title' => $this->title, 'status' => 'draft', 'meta_description' => $this->meta_description, 'order' => '999', 'slug' => $newpageslug]);
        $this->FormAdd = false;
        return redirect()->to('/admin/pages/show/'.$page->id);
    }

    public function render()
    {
        return view('kompass::livewire.pages.pages-table', ['pages' => $this->resultDate()])->layout('kompass::admin.layouts.app');
    }

    public function handleSort($item, $position)
    {
        $pages = Page::orderBy('order', 'ASC')->get();
        $movedItemIndex = $pages->search(fn ($page) => $page->id == $item);
        if ($movedItemIndex === false) return;
        $movedItem = $pages->pull($movedItemIndex);
        $pages->splice($position, 0, [$movedItem]);
        foreach ($pages->values() as $index => $page) {
            if ($page->order !== $index) $page->update(['order' => $index]);
        }
        $this->call_emit_reset();
    }
}
