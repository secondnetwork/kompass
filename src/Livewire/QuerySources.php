<?php

namespace Secondnetwork\Kompass\Livewire;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Models\QuerySource;

class QuerySources extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    public string $orderBy = 'order';

    public bool $orderAsc = true;

    public bool $FormAdd = false;

    public ?int $editId = null;

    public string $key = '';

    public string $label = '';

    public string $model_key = '';

    public string $display_fields = 'title';

    public string $order_fields = 'created_at';

    public string $url_pattern = '';

    public string $status_filter = '';

    public string $scope = '';

    public string $item_view = '';

    public string $wrapper_class = '';

    public string $with = '';

    public int $order = 0;

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return [
            'key' => 'required|alpha_dash|max:100|unique:query_sources,key,'.($this->editId ?? 'NULL').',id',
            'label' => 'required|string|max:255',
            'model_key' => 'required|string|in:'.implode(',', array_keys($this->modelOptions())),
            'display_fields' => 'required|string|max:255',
            'order_fields' => 'nullable|string|max:255',
            'url_pattern' => 'nullable|string|max:255',
            'status_filter' => 'nullable|string|max:100',
            'scope' => 'nullable|string|max:100',
            'item_view' => 'nullable|string|max:255',
            'wrapper_class' => 'nullable|string|max:255',
            'with' => 'nullable|string|max:255',
            'order' => 'integer',
        ];
    }

    /**
     * Allow-listed models a source may be backed by (key => human label).
     *
     * @return array<string, string>
     */
    public function modelOptions(): array
    {
        $options = [];
        foreach (config('kompass.query_source_models', []) as $key => $class) {
            $options[$key] = $key.' ('.class_basename($class).')';
        }

        return $options;
    }

    /**
     * Existing frontend item views (components/relations/*.blade.php),
     * excluding partials prefixed with "_".
     *
     * @return array<int, string>
     */
    public function itemViewOptions(): array
    {
        $dir = resource_path('views/components/relations');

        if (! File::isDirectory($dir)) {
            return [];
        }

        return collect(File::glob($dir.'/*.blade.php'))
            ->map(fn ($path) => basename($path, '.blade.php'))
            ->reject(fn ($name) => str_starts_with($name, '_'))
            ->map(fn ($name) => 'relations.'.$name)
            ->values()
            ->all();
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
        $this->reset(['editId', 'key', 'label', 'model_key', 'url_pattern', 'status_filter', 'scope', 'item_view', 'wrapper_class', 'with']);
        $this->display_fields = 'title';
        $this->order_fields = 'created_at';
        $this->order = ((int) QuerySource::max('order')) + 1; // append to the end
        $this->FormAdd = true;
    }

    /**
     * Drag-and-drop reordering of the sources list. `wire:sort` passes the
     * dragged row's id and its new 0-based position within the rendered page;
     * we move it in the globally ordered list and renumber `order` 1..n.
     */
    public function reorder(int $id, int $position): void
    {
        // Use the same ordering as the rendered table so the drop position
        // always maps to what the user sees, then renumber `order` 1..n.
        $ids = QuerySource::orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->orderBy('id')
            ->pluck('id')
            ->all();

        $from = array_search($id, $ids, true);
        if ($from === false) {
            return;
        }

        array_splice($ids, $from, 1);

        // Translate the page-relative position into a global index.
        $target = (($this->getPage() - 1) * 20) + $position;
        $target = max(0, min($target, count($ids)));
        array_splice($ids, $target, 0, [$id]);

        foreach ($ids as $index => $sourceId) {
            QuerySource::whereKey($sourceId)->update(['order' => $index + 1]);
        }
    }

    public function editItem($id): void
    {
        $source = QuerySource::findOrFail($id);

        $this->resetValidation();
        $this->editId = $source->id;
        $this->key = $source->key;
        $this->label = $source->label;
        $this->model_key = $source->model_key;
        $this->display_fields = implode(', ', $source->display_fields ?: ['title']);
        $this->order_fields = implode(', ', $source->order_fields ?? []);
        $this->url_pattern = (string) $source->url_pattern;
        $this->status_filter = (string) $source->status_filter;
        $this->scope = (string) $source->scope;
        $this->item_view = (string) $source->item_view;
        $this->wrapper_class = (string) $source->wrapper_class;
        $this->with = implode(', ', $source->with ?? []);
        $this->order = (int) $source->order;
        $this->FormAdd = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'label' => $this->label,
            'model_key' => $this->model_key,
            'display_fields' => $this->splitList($this->display_fields),
            'order_fields' => $this->splitList($this->order_fields),
            'url_pattern' => $this->url_pattern ?: null,
            'status_filter' => $this->status_filter ?: null,
            'scope' => $this->scope ?: null,
            'item_view' => $this->item_view ?: null,
            'wrapper_class' => $this->wrapper_class ?: null,
            'with' => $this->splitList($this->with),
            'order' => $this->order,
        ];

        // The key is immutable once created — saved blocks reference it.
        if (! $this->editId) {
            $data['key'] = Str::slug($this->key);
        }

        QuerySource::updateOrCreate(['id' => $this->editId], $data);

        $this->FormAdd = false;
        $this->reset(['editId', 'key', 'label', 'model_key', 'display_fields', 'url_pattern', 'status_filter', 'scope', 'item_view', 'wrapper_class', 'with']);
        $this->resetPage();
    }

    public function delete($id): void
    {
        QuerySource::whereKey($id)->delete();
        $this->resetPage();
    }

    /**
     * Split a comma/whitespace separated string into a clean list.
     *
     * @return array<int, string>
     */
    private function splitList(string $value): array
    {
        return collect(preg_split('/[,\s]+/', trim($value)))
            ->filter()
            ->values()
            ->all();
    }

    private function resultData()
    {
        return QuerySource::query()
            ->when($this->search !== '', function ($query): void {
                $query->where('label', 'like', '%'.$this->search.'%')
                    ->orWhere('key', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate(20);
    }

    public function render()
    {
        return view('kompass::livewire.query-sources', [
            'sources' => $this->resultData(),
            'modelOptions' => $this->modelOptions(),
            'itemViewOptions' => $this->itemViewOptions(),
        ])->layout('kompass::admin.layouts.app');
    }
}
