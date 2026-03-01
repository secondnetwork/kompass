<?php

namespace Secondnetwork\Kompass\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Models\Block;
use Secondnetwork\Kompass\Models\Post;

class PostsTable extends Component
{
    /**
     * The component's listeners.
     *
     * @var array
     */
    use WithPagination;

    public $search;

    protected $queryString = ['search'];

    public $perPost = 1000;

    public $orderBy = 'order';

    public $orderAsc = true;

    public $tasks;

    public $data;

    public $title;

    public $headers;

    public $meta_description;

    public $land = '';

    public $available_locales;

    #[Locked]
    public $selectedItem;

    public $FormDelete = false;

    public $FormAdd = false;

    public $FormClone = false;

    public $FormEdit = false;

    public $cloneLand = '';

    protected $rules = [

        'title' => 'unique:posts|required|string|min:3',
        'meta_description' => '',

    ];

    protected function headerTable(): array
    {
        return [
            'title',
            'land',
            'status',
            'Updated',
            '',
        ];
    }

    protected function dataTable(): array
    {
        return [
            'title',
            'land',
            'status',
            'updated_at',
        ];
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

    public function resetpost()
    {
        $this->resetpage();
    }

    private function resultDate()
    {
        $query = Post::query();

        if ($this->search) {
            $query->where('title', 'like', '%'.$this->search.'%');
        }

        if ($this->land) {
            $query->where('land', $this->land);
        }

        return $query->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->simplePaginate($this->perPost);
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;
        if ($action == 'add') {
            $this->FormAdd = true;
        }
        if ($action == 'delete') {
            $this->FormDelete = true;
        }
        if ($action == 'clone') {
            $this->FormClone = true;
            $this->cloneLand = Post::find($itemId)->land ?? config('app.locale', 'de');
        }
    }

    public function status($id, $status)
    {
        Post::where('id', $id)->update(['status' => $status]);
    }

    public function addPost()
    {
        $this->validate();

        $slugNameURL = Str::slug($this->title, '-', 'de'); // Convert Input to Str Slug

        $placeObj = new Post;

        $checkSlug = $placeObj->whereSlug($slugNameURL)->exists();

        if ($checkSlug) {
            $numericalPrefix = 1;
            while (1) {
                $newSlug = $slugNameURL.'-'.$numericalPrefix++;
                $newSlug = Str::slug($newSlug, '-', 'de');
                $checkSlug = $placeObj->whereSlug($newSlug)->exists();
                if (! $checkSlug) {
                    $newpostslug = $newSlug; // New Slug
                    break;
                }
            }
        } else {
            // Slug do not exists. Just use the selected Slug.
            $newpostslug = $slugNameURL;
        }

        $post = Post::create([
            'title' => $this->title,
            'status' => 'draft',
            'meta_description' => $this->meta_description,
            'slug' => $newpostslug,
            'land' => $this->land ?: 'de',
        ]);
        $this->FormAdd = false;

        return redirect()->to('/admin/posts/show/'.$post->id);
    }

    public function clonePage()
    {
        $id = $this->selectedItem;
        $post = Post::find($id);

        $newpost = $post->replicate();

        $slugNameURL = Str::slug($newpost['title'], '-', 'de'); // Convert Input to Str Slug

        $placeObj = new Post;

        $checkSlug = $placeObj->whereSlug($slugNameURL)->exists();

        if ($checkSlug) {
            $numericalPrefix = 1;
            while (1) {
                $newSlug = $slugNameURL.'-'.$numericalPrefix++;
                $newSlug = Str::slug($newSlug, '-', 'de');
                $checkSlug = $placeObj->whereSlug($newSlug)->exists();
                if (! $checkSlug) {
                    $newpost->slug = $newSlug; // New Slug
                    break;
                }
            }
        } else {
            // Slug do not exists. Just use the selected Slug.
            $newpost->slug = $slugNameURL;
        }
        $newpost->status = 'draft';
        $newpost->created_at = Carbon::now();
        $newpost->land = $this->cloneLand;

        $newpost->push();

        $relationships = ['datafield', 'meta', 'children'];
        $blocksclone = Block::where('blockable_id', $id)->where('blockable_type', 'post')->with($relationships)->get();

        $rootblock = $blocksclone->whereNull('subgroup');

        foreach ($rootblock as $item) {
            $blockcopy = $item->replicate();
            $blockcopy->blockable_id = $newpost->id;
            $blockcopy->push();

            if ($itemMeta = $item->allMeta) {
                $mod = Block::find($blockcopy->id);
                foreach ($itemMeta as $value) {
                    $mod->saveMeta([
                        $value->key => $value->value,
                    ]);
                }
            }

            foreach ($item->datafield as $itemdata) {
                $copydatablock = $itemdata->replicate();
                $copydatablock->block_id = $blockcopy->id;
                $copydatablock->push();
            }

            $children = $blocksclone->where('subgroup', $item->id);
            $this->cloneTree($children, $blocksclone, $newpost->id, $blockcopy->id);
        }
        $this->FormClone = false;
        return redirect()->to('/admin/posts/show/'.$newpost->id);
    }

    public function cloneTree($categories, $allCategories, $cloneid, $parentId)
    {
        foreach ($categories as $item) {
            $copy = $item->replicate();
            $copy->blockable_id = $cloneid;
            $copy->subgroup = $parentId;
            $copy->push();

            if ($itemMeta = $item->allMeta) {
                $mod = Block::find($copy->id);

                foreach ($itemMeta as $value) {
                    $mod->saveMeta([
                        $value->key => $value->value,
                    ]);
                }
            }

            foreach ($item->datafield as $itemdata) {
                $copydatablock = $itemdata->replicate();
                $copydatablock->block_id = $copy->id;
                $copydatablock->push();
            }

            $children = $allCategories->where('subgroup', $item->id);
            if ($children->isNotEmpty()) {
                $this->cloneTree($children, $allCategories, $cloneid, $copy->id);
            }
        }
    }

    public function delete()
    {

        Post::find($this->selectedItem)->delete();
        $this->FormDelete = false;

    }

    public function addate()
    {
        post::create($this->form->getState());
        Post::where('deleted_at');
    }

    public function render()
    {
        return view('kompass::livewire.posts.posts-table', [
            'posts' => $this->resultDate(),
        ])->layout('kompass::admin.layouts.app');
    }
}
