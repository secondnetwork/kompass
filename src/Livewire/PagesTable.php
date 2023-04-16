<?php

namespace Secondnetwork\Kompass\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Models\Block;
use Secondnetwork\Kompass\Models\Datafields;
use Secondnetwork\Kompass\Models\Page;

class PagesTable extends Component
{
    /**
     * The component's listeners.
     *
     * @var array
     */
    use WithPagination;

    public $search;

    protected $queryString = ['search'];

    public $perPage = 1000;

    public $orderBy = 'order';

    public $orderAsc = true;

    public $tasks;

    public $data;

    public $title;

    public $meta_description;

    public $timestamps = false;

    public $FormDelete = false;

    public $FormAdd = false;

    public $FormEdit = false;

    protected $rules = [

        'title' => 'unique:pages|required|string|min:3',
        'meta_description' => '',

    ];

    protected function headerTable(): array
    {
        return [
            '',
            'title',
            // 'thumbnails',
            // 'description',
            'slug',
            'status',
            'Updated',
            '',
        ];
    }

    protected function dataTable(): array
    {
        return [
            'title',
            // 'thumbnails',
            // 'meta_description',
            'slug',
            'status',
            'updated_at',
        ];
    }

    public function mount()
    {
        $this->headers = $this->headerTable();
        $this->data = $this->dataTable();
        // $this->form->fill();
    }

    public function resetpage()
    {
        $this->mount($this->page_id);
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

    public function status($id, $status)
    {
        if ($status == 'unpublish') {
            Page::where('id', $id)->update(['status' => 'unpublish']);
        }
        if ($status == 'public') {
            Page::where('id', $id)->update(['status' => 'public']);
        }

        // $this->resetpage();
    }

    public function addPage()
    {
        $this->validate();

        $slugNameURL = Str::slug($this->title, '-', 'de'); //Convert Input to Str Slug

        $placeObj = new Page;

        $checkSlug = $placeObj->whereSlug($slugNameURL)->exists();

        if ($checkSlug) {
            $numericalPrefix = 1;
            while (1) {
                $newSlug = $slugNameURL.'-'.$numericalPrefix++;
                $newSlug = Str::slug($newSlug, '-', 'de');
                $checkSlug = $placeObj->whereSlug($newSlug)->exists();
                if (! $checkSlug) {
                    $newpageslug = $newSlug; //New Slug
                    break;
                }
            }
        } else {
            //Slug do not exists. Just use the selected Slug.
            $newpageslug = $slugNameURL;
        }

        $page = Page::create([

            'title' => $this->title,
            'status' => 'unpublish',
            'meta_description' => $this->meta_description,
            'order' => '999',
            'slug' => $newpageslug,
            // 'slug' => generateSlug($this->title)

        ]);
        $this->FormAdd = false;

        return redirect()->to('/admin/pages/show/'.$page->id);
    }

    public function clone($id)
    {
        $page = Page::find($id);

        $newpage = $page->replicate();

        $slugNameURL = Str::slug($newpage['title'], '-', 'de'); //Convert Input to Str Slug

        $placeObj = new Page;

        $checkSlug = $placeObj->whereSlug($slugNameURL)->exists();

        if ($checkSlug) {
            $numericalPrefix = 1;
            while (1) {
                $newSlug = $slugNameURL.'-'.$numericalPrefix++;
                $newSlug = Str::slug($newSlug, '-', 'de');
                $checkSlug = $placeObj->whereSlug($newSlug)->exists();
                if (! $checkSlug) {
                    $newpage->slug = $newSlug; //New Slug
                    break;
                }
            }
        } else {
            //Slug do not exists. Just use the selected Slug.
            $newpage->slug = $slugNameURL;
        }
        $newpage->status = 'unpublish';
        $newpage->created_at = Carbon::now();

        $newpage->push();

        $blocksclone = Block::where('page_id', $id)->orderBy('order', 'asc')->where('subgroup', null)->with('children')->get();

        $blocksclone->each(function ($item, $key) use ($newpage) {
            $altID = $item->id;

            $copy = $item->replicate();

            $copy->page_id = $newpage->id;
            $copy->save();
            if ($copy->children) {
                foreach ($copy->children as $subgroup) {
                    $copygroup = $subgroup->replicate();
                    $copygroup->page_id = $newpage->id;
                    $copygroup->subgroup = $copy->id;
                    $copygroup->save();
                }
            }

            $fields = Datafields::where('block_id', $altID)->get();
            $fields->each(function ($item, $key) use ($copy) {
                $copyitem = $item->replicate();
                $copyitem->block_id = $copy->id;
                $copyitem->save();
            }, );
        }, );
    }

    public function delete()
    {
        Page::find($this->selectedItem)->delete();

        $blocks_id = Block::where('page_id', $this->selectedItem)->orderBy('order', 'asc')->pluck('id');

        Arr::collapse($blocks_id);

        Block::where('page_id', $this->selectedItem)->delete();

        $this->fields = Datafields::whereIn('block_id', $blocks_id)->delete();

        $this->FormDelete = false;
    }

    public function addate()
    {
        // dd($this->form->getState()); page::create
        page::create($this->form->getState());
        Page::where('deleted_at');
    }

    public function render()
    {
        return view('kompass::livewire.pages.pages-table', [
            'pages' => $this->resultDate(),
        ])->layout('kompass::admin.layouts.app');
    }

    public function updateOrder($list)
    {
        foreach ($list as $item) {
            // $pageid = Page::whereId($item['value']);
            // $pageid->timestamps = false;
            // $pageid->order = $item['order'];
            // $pageid->update();
            Page::whereId($item['value'])->update(['order' => $item['order']]);
            // foreach($itemg['items'] as $item){
            //     Page::whereId($item['value'])->update(['order' => $item['order']]);
            // }
        }


        // Page::whereId($list['value'])->update(['order' => $list['order']]);
    }
}
