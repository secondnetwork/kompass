<?php

namespace Secondnetwork\Kompass\Livewire;

use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Models\Category;
use Illuminate\Support\Facades\File;

class CategoryTable extends Component
{
    use WithPagination;

    public $search;
    public $orderBy = 'name';
    public $orderAsc = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'orderBy' => ['except' => 'order'],
        'orderAsc' => ['except' => true],
    ];

    public $name;
    public $slug;
    public $description;
    public $color = 'primary';
    public $icon = '';
    public $iconSearch = '';
    public $selectedIcon = '';
    public $filteredIcons = [];
    public $order = 0;
    public $perPage = 20;
    public $headers;
    public $data;

    #[Locked]
    public $selectedItem;

    public $FormDelete = false;
    public $FormAdd = false;
    public $FormEdit = false;

    protected $rules = [
        'name' => 'required|string|min:2',
        'slug' => 'nullable|string|unique:categories,slug',
        'description' => 'nullable|string',
        'color' => 'nullable|string',
        'icon' => 'nullable|string',
        'order' => 'nullable|integer',
    ];

    protected function headerTable(): array
    {
        return ['name', 'color', 'icon' ,''];
    }

    protected function dataTable(): array
    {
        return ['name', 'color', 'icon'];
    }

    public function mount()
    {
        $this->headers = $this->headerTable();
        $this->data = $this->dataTable();
        $this->loadIcons();
    }

    private function getIconPath(): string
    {
        $possiblePaths = [
            base_path('vendor/secondnetwork/blade-tabler-icons/resources/svg'),
            dirname(base_path()) . '/vendor/secondnetwork/blade-tabler-icons/resources/svg',
            public_path('vendor/blade-tabler-icons'),
        ];

        foreach ($possiblePaths as $path) {
            if (is_dir($path)) {
                return $path;
            }
        }

        return '';
    }

    public function loadIcons()
    {
        $this->filteredIcons = [];

        $iconPath = $this->getIconPath();
        
        if (empty($iconPath) || !is_dir($iconPath)) {
            return;
        }

        try {
            $files = File::files($iconPath);
            
            if (empty($files)) {
                return;
            }
            
            $icons = collect($files)
                ->map(fn($file) => str_replace('.svg', '', $file->getFilename()))
                ->sort()
                ->values();

            if ($this->iconSearch) {
                $search = strtolower(trim($this->iconSearch));
                if (!empty($search)) {
                    $icons = $icons->filter(fn($name) =>
                        str_contains(strtolower($name), $search)
                    );
                }
            }

            $this->filteredIcons = $icons->take(100)->map(fn($name) => [
                'id' => $name,
                'name' => $name,
            ])->values()->toArray();
        } catch (\Exception $e) {
            $this->filteredIcons = [];
        }
    }

    public function updatedIconSearch()
    {
        $this->loadIcons();
    }

    public function selectIcon($name)
    {
        $this->icon = $name;
        $this->selectedIcon = $name;
    }

    public function resetIcon()
    {
        $this->icon = '';
        $this->selectedIcon = '';
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

    private function resultDate()
    {
        $results = Category::query();
        if ($this->search) {
            $results->where('name', 'like', '%'.$this->search.'%')
                ->orWhere('slug', 'like', '%'.$this->search.'%');
        }
        return $results->orderBy($this->orderBy, $this->orderAsc ? 'ASC' : 'DESC')->paginate($this->perPage);
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;
        if ($action == 'add') {
            $this->FormAdd = true;
            $this->resetFields();
        }
        if ($action == 'edit') {
            $this->FormEdit = true;
            $this->loadCategory($itemId);
        }
        if ($action == 'delete') $this->FormDelete = true;
    }

    private function resetFields()
    {
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->color = 'primary';
        $this->icon = '';
        $this->selectedIcon = '';
        $this->iconSearch = '';
        $this->order = 0;
    }

    private function loadCategory($id)
    {
        $category = Category::findOrFail($id);
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description;
        $this->color = $category->color ?? 'primary';
        $this->icon = $category->icon ?? '';
        $this->selectedIcon = $category->icon ?? '';
        $this->order = $category->order;
        $this->selectedItem = $category->id;
    }

    public function updatedName($value)
    {
        if (empty($this->slug)) {
            $this->slug = Str::slug($value, '-', 'de');
        }
    }

    public function delete()
    {
        Category::find($this->selectedItem)->delete();
        $this->FormDelete = false;
        $this->resetFields();
    }

    public function save()
    {
        $this->validate();

        $slug = $this->slug ?: Str::slug($this->name, '-', 'de');

        $categoryObj = new Category;
        $numericalPrefix = 1;
        while ($categoryObj->whereSlug($slug)->exists()) {
            $slug = Str::slug($this->name, '-', 'de').'-'.$numericalPrefix++;
        }

        Category::create([
            'name' => $this->name,
            'slug' => $slug,
            'description' => $this->description,
            'color' => $this->color,
            'icon' => $this->icon,
            'order' => $this->order,
        ]);

        $this->FormAdd = false;
        $this->resetFields();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|min:2',
            'slug' => 'nullable|string|unique:categories,slug,'.$this->selectedItem,
            'description' => 'nullable|string',
            'color' => 'nullable|string',
            'icon' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $slug = $this->slug ?: Str::slug($this->name, '-', 'de');

        $category = Category::findOrFail($this->selectedItem);

        $categoryObj = new Category;
        $numericalPrefix = 1;
        while ($categoryObj->whereSlug($slug)->where('id', '!=', $this->selectedItem)->exists()) {
            $slug = Str::slug($this->name, '-', 'de').'-'.$numericalPrefix++;
        }

        $category->update([
            'name' => $this->name,
            'slug' => $slug,
            'description' => $this->description,
            'color' => $this->color,
            'icon' => $this->icon,
            'order' => $this->order,
        ]);

        $this->FormEdit = false;
        $this->resetFields();
    }

    public function render()
    {
        return view('kompass::livewire.categories.category-table', ['categories' => $this->resultDate()])->layout('kompass::admin.layouts.app');
    }
}
