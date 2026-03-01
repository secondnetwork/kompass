<?php

namespace Secondnetwork\Kompass\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Models\Block;
use Secondnetwork\Kompass\Models\Datafield;
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
    public $land = '';
    public $available_locales;

    #[Locked]
    public $selectedItem;

    public $timestamps = false;
    public $FormDelete = false;
    public $FormAdd = false;
    public $FormClone = false;
    public $FormEdit = false;
    public $cloneLand = '';

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
        
        $locales = ['de', 'en', 'tr'];
        $appLocale = config('app.locale', 'de');
        
        // Move app locale to front
        if (($key = array_search($appLocale, $locales)) !== false) {
            unset($locales[$key]);
            array_unshift($locales, $appLocale);
        }
        
        $this->available_locales = $locales;
        $this->land = $appLocale;
    }

    private function resultDate()
    {
        $query = Page::query();

        if ($this->search) {
            $query->where('title', 'like', '%'.$this->search.'%');
        }

        if ($this->land) {
            $query->where('land', $this->land);
        }

        return $query
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->simplePaginate($this->perPage);
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;
        if ($action == 'add') $this->FormAdd = true;
        if ($action == 'delete') $this->FormDelete = true;
        if ($action == 'clone') {
            $this->FormClone = true;
            $this->cloneLand = Page::find($itemId)->land ?? config('app.locale', 'de');
        }
    }

    public function status($id, $status)
    {
        Page::where('id', $id)->update(['status' => $status]);
    }

    public function delete()
    {
        Page::find($this->selectedItem)->delete();
        $this->FormDelete = false;
    }

    public function clonePage()
    {
        $id = $this->selectedItem;
        $originalPage = Page::findOrFail($id);
        
        $newTitle = $originalPage->title . ' (copy)';
        $slugNameURL = Str::slug($originalPage->title, '-', 'de');
        $newSlug = $slugNameURL . '-copy';
        
        $pageObj = new Page;
        $numericalPrefix = 1;
        while ($pageObj->whereSlug($newSlug)->exists()) {
            $newSlug = $slugNameURL . '-copy-' . $numericalPrefix++;
        }

        $newPage = $originalPage->replicate();
        $newPage->title = $newTitle;
        $newPage->slug = $newSlug;
        $newPage->order = 999;
        $newPage->status = 'draft';
        $newPage->land = $this->cloneLand;
        $newPage->push();

        $blocks = Block::where('blockable_type', 'page')->where('blockable_id', $id)->get();
        
        foreach ($blocks as $block) {
            $newBlock = $block->replicate();
            $newBlock->blockable_id = $newPage->id;
            $newBlock->created_at = Carbon::now();
            $newBlock->updated_at = Carbon::now();
            $newBlock->push();

            $datafields = Datafield::where('block_id', $block->id)->get();
            foreach ($datafields as $datafield) {
                $newDatafield = $datafield->replicate();
                $newDatafield->block_id = $newBlock->id;
                $newDatafield->created_at = Carbon::now();
                $newDatafield->updated_at = Carbon::now();
                $newDatafield->save();
            }
        }
        $this->FormClone = false;
        return redirect()->to('/admin/pages/show/'.$newPage->id);
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

        $page = Page::create([
            'title' => $this->title,
            'status' => 'draft',
            'meta_description' => $this->meta_description,
            'order' => '999',
            'slug' => $newpageslug,
            'land' => $this->land ?: 'de',
        ]);
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
