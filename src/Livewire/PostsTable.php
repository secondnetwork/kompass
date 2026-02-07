<?php

namespace Secondnetwork\Kompass\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Models\Block;
use Secondnetwork\Kompass\Models\Datafield;
use Secondnetwork\Kompass\Models\Post;

class PostsTable extends Component
{
    use WithPagination;

    public $search;
    public $orderBy = 'created_at';
    public $orderAsc = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'orderBy' => ['except' => 'updated_at'],
        'orderAsc' => ['except' => false],
    ];

    public $perPost = 1000;
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

    public function call_emit_reset()
    {
        $this->dispatch('status');
    }

    protected function headerTable(): array
    {
        return ['title', 'status', 'updated_at', ''];
    }

    protected function dataTable(): array
    {
        return ['title', 'status', 'updated_at'];
    }

    public function mount()
    {
        $this->headers = $this->headerTable();
        $this->data = $this->dataTable();
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

    public function resetpost()
    {
        $this->resetpage();
    }

    private function resultDate()
    {
        $results = Post::query();
        if ($this->search) {
            $results->where('title', 'like', '%'.$this->search.'%');
        }
        return $results->orderBy($this->orderBy, $this->orderAsc ? 'ASC' : 'DESC')->get();
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;
        if ($action == 'add') $this->FormAdd = true;
        if ($action == 'delete') $this->FormDelete = true;
    }

    public function status($id, $status)
    {
        if ($status == 'draft') Post::where('id', $id)->update(['status' => 'draft']);
        if ($status == 'published') Post::where('id', $id)->update(['status' => 'published']);
    }

    public function delete()
    {
        Post::find($this->selectedItem)->delete();
        $this->FormDelete = false;
    }

    public function clone($id)
    {
        $originalPost = Post::findOrFail($id);
        
        $newTitle = $originalPost->title . ' (copy)';
        $slugNameURL = Str::slug($originalPost->title, '-', 'de');
        $newSlug = $slugNameURL . '-copy';
        
        $postObj = new Post;
        $numericalPrefix = 1;
        while ($postObj->whereSlug($newSlug)->exists()) {
            $newSlug = $slugNameURL . '-copy-' . $numericalPrefix++;
        }

        $newPost = $originalPost->replicate();
        $newPost->title = $newTitle;
        $newPost->slug = $newSlug;
        $newPost->created_at = Carbon::now();
        $newPost->updated_at = Carbon::now();
        $newPost->status = 'draft';
        $newPost->push();

        $blocks = Block::where('blockable_type', 'post')->where('blockable_id', $id)->get();
        
        foreach ($blocks as $block) {
            $newBlock = $block->replicate();
            $newBlock->blockable_id = $newPost->id;
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
    }

    public function addPost()
    {
        $this->validate();
        $slugNameURL = Str::slug($this->title, '-', 'de');
        $placeObj = new Post;
        if ($placeObj->whereSlug($slugNameURL)->exists()) {
            $numericalPrefix = 1;
            while (1) {
                $newSlug = $slugNameURL.'-'.$numericalPrefix++;
                $newSlug = Str::slug($newSlug, '-', 'de');
                if (!$placeObj->whereSlug($newSlug)->exists()) {
                    $newpostslug = $newSlug;
                    break;
                }
            }
        } else {
            $newpostslug = $slugNameURL;
        }

        $post = Post::create(['title' => $this->title, 'status' => 'draft', 'meta_description' => $this->meta_description, 'slug' => $newpostslug]);
        $this->FormAdd = false;
        return redirect()->to('/admin/posts/show/'.$post->id);
    }

    public function render()
    {
        return view('kompass::livewire.posts.posts-table', ['posts' => $this->resultDate()])->layout('kompass::admin.layouts.app');
    }

    public function toJSON(): string { return '{}'; }
}
