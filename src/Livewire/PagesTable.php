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
        if ($status == 'draft') {
            Page::where('id', $id)->update(['status' => 'draft']);
        }
        if ($status == 'published') {
            Page::where('id', $id)->update(['status' => 'published']);
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
            'status' => 'draft',
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
        $pagemodel = Page::find($id);

        $slugNameURL = $pagemodel['slug']; //Convert Input to Str Slug

        $placeObj = new Page;

        $checkSlug = $placeObj->whereSlug($slugNameURL)->exists();

        if ($checkSlug) {
            $numericalPrefix = 1;
            while (1) {
                $newSlug = $slugNameURL.'-'.$numericalPrefix++;
                $newSlug = Str::slug($newSlug, '-', 'de');
                $checkSlug = $placeObj->whereSlug($newSlug)->exists();
                if (! $checkSlug) {
                    $clonepageslug = $newSlug; //New Slug
                    break;
                }
            }
        } else {
            //Slug do not exists. Just use the selected Slug.
            $clonepageslug = $slugNameURL;
        }

        $clone = $pagemodel->replicate()->fill([
            'slug' => $clonepageslug,
            'status' => 'draft',
            'created_at' => Carbon::now(),
        ]);

        $clone->push();

        $relationships = ['datafield', 'meta', 'children'];

        $blocksclone = Block::where('blockable_id', $id)->where('blockable_type', 'page')->with($relationships)->get();

        $rootblock = $blocksclone->whereNull('subgroup');

        // $rootblock->each(function ($item) use ($clone, $rootblock, $blocksclone) {

        foreach ($rootblock as $item) {

            $blockcopy = $item->replicate();

            $blockcopy->blockable_id = $clone->id;

            $blockcopy->push();

            if ($itemMeta = $item->allMeta) {
                $mod = Block::find($blockcopy->id);

                foreach ($itemMeta as $value) {

                    $mod->saveMeta([
                        $value->key => $value->value,
                    ]);
                }
            }

            foreach ($blockcopy->datafield as $itemdata) {
                $copydatablock = $itemdata->replicate();
                $copydatablock->block_id = $blockcopy->id;
                $copydatablock->push();
            }

            foreach ($blockcopy->children as $subgroup) {

                $copygroup = $subgroup->replicate();
                $copygroup->blockable_id = $clone->id;
                $copygroup->subgroup = $blockcopy->id;
                $copygroup->push();

                if ($itemMeta = $subgroup->allMeta) {
                    $mod = Block::find($copygroup->id);

                    foreach ($itemMeta as $value) {
                        $mod->saveMeta([
                            $value->key => $value->value,
                        ]);
                    }
                }

                foreach ($subgroup->datafield as $itemdata) {
                    $copydatablock = $itemdata->replicate();
                    $copydatablock->block_id = $copygroup->id;
                    $copydatablock->push();
                }
            }

            self::cloneTree($blockcopy->children, $blocksclone, $clone->id, $copygroup->id);
        }

        $this->resetpage();
    }

    public function cloneTree($categories, $allCategories, $cloneid, $blockid)
    {
        foreach ($categories as $item) {

            $item->children = $allCategories->where('subgroup', $item->id);

            if ($item->children->isNotEmpty()) {
                foreach ($item->children as $subgroup) {

                    $copygroup = $subgroup->replicate();
                    $copygroup->blockable_id = $cloneid;
                    $copygroup->subgroup = $blockid;
                    $copygroup->push();

                    if ($itemMeta = $subgroup->allMeta) {
                        $mod = Block::find($copygroup->id);

                        foreach ($itemMeta as $value) {
                            $mod->saveMeta([
                                $value->key => $value->value,
                            ]);
                        }
                    }

                    foreach ($subgroup->datafield as $itemdata) {
                        $copydatablock = $itemdata->replicate();
                        $copydatablock->block_id = $copygroup->id;
                        $copydatablock->push();
                    }
                }

                self::cloneTree($item->children, $allCategories, $cloneid, $copygroup->id);
            }
        }
    }

    public function delete()
    {

        Page::find($this->selectedItem)->delete();

        $this->FormDelete = false;
    }

    public function addate()
    {
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
