<?php

namespace Secondnetwork\Kompass\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Models\Block;
use Secondnetwork\Kompass\Models\Datafields;
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
        $this->mount($this->post_id);
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

        $slugNameURL = Str::slug($this->title, '-', 'de'); //Convert Input to Str Slug

        $placeObj = new Post;

        $checkSlug = $placeObj->whereSlug($slugNameURL)->exists();

        if ($checkSlug) {
            $numericalPrefix = 1;
            while (1) {
                $newSlug = $slugNameURL.'-'.$numericalPrefix++;
                $newSlug = Str::slug($newSlug, '-', 'de');
                $checkSlug = $placeObj->whereSlug($newSlug)->exists();
                if (! $checkSlug) {
                    $newpostslug = $newSlug; //New Slug
                    break;
                }
            }
        } else {
            //Slug do not exists. Just use the selected Slug.
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

        $slugNameURL = Str::slug($newpost['title'], '-', 'de'); //Convert Input to Str Slug

        $placeObj = new Post;

        $checkSlug = $placeObj->whereSlug($slugNameURL)->exists();

        if ($checkSlug) {
            $numericalPrefix = 1;
            while (1) {
                $newSlug = $slugNameURL.'-'.$numericalPrefix++;
                $newSlug = Str::slug($newSlug, '-', 'de');
                $checkSlug = $placeObj->whereSlug($newSlug)->exists();
                if (! $checkSlug) {
                    $newpost->slug = $newSlug; //New Slug
                    break;
                }
            }
        } else {
            //Slug do not exists. Just use the selected Slug.
            $newpost->slug = $slugNameURL;
        }
        $newpost->status = 'draft';
        $newpost->created_at = Carbon::now();

        $newpost->push();

        $blocksclone = Block::where('post_id', $id)->orderBy('order', 'asc')->where('subgroup', null)->with('children')->get();

        $blocksclone->each(function ($item, $key) use ($newpost) {
            $altID = $item->id;

            $copy = $item->replicate();

            $copy->post_id = $newpost->id;
            $copy->save();
            if ($copy->children) {
                foreach ($copy->children as $subgroup) {
                    $copygroup = $subgroup->replicate();
                    $copygroup->post_id = $newpost->id;
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

        Post::find($this->selectedItem)->delete();

        $blocks_id = Block::where('post_id', $this->selectedItem)->orderBy('order', 'asc')->pluck('id');

        Arr::collapse($blocks_id);

        Block::where('post_id', $this->selectedItem)->delete();

        $this->fields = Datafields::whereIn('block_id', $blocks_id)->delete();

        $this->FormDelete = false;
    }

    public function addate()
    {
        // dd($this->form->getState()); post::create
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
