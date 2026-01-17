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

    #[Locked]
    public $selectedItem;

    public $FormDelete = false;

    public $FormAdd = false;

    public $FormEdit = false;

    protected $rules = [

        'title' => 'unique:posts|required|string|min:3',
        'meta_description' => '',

    ];

    protected function headerTable(): array
    {
        return [

            'title',
            // 'thumbnails',
            // 'description',

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

    public function resetpost()
    {
        $this->resetpage();
    }

    private function resultDate()
    {

        $results = Post::query();

        if ($results->count() > 0) {
            return $results->orderBy('created_at', 'DESC')->get();
        }

        return $results;
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
            Post::where('id', $id)->update(['status' => 'draft']);
        }
        if ($status == 'published') {
            Post::where('id', $id)->update(['status' => 'published']);
        }

        // $this->resetpost();
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
            // 'slug' => generateSlug($this->title)

        ]);
        $this->FormAdd = false;

        return redirect()->to('/admin/posts/show/'.$post->id);
    }

    public function clone($id)
    {
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

        $this->resetpage();
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
