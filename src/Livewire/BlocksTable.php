<?php

namespace Secondnetwork\Kompass\Livewire;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Models\Blocktemplates;

class BlocksTable extends Component
{
    use WithPagination;

    public $headers;
    public $data;
    public $action;
    public $selectedItem;
    public $FormDelete = false;
    public $FormAdd = false;
    public $FormEdit = false;
    public $FormRegenerate = false;
    public $regenerateId = null;
    public $regenerateFileName = '';
    public $perPage = 10000;
    public $search = '';
    public $orderBy = 'order';
    public $orderAsc = true;
    public $blockarray;
    public $name;
    public $type;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:1',
            'type' => [
                'required',
                'string',
                'min:1',
                'unique:blocktemplates,type',
                Rule::notIn(array_keys(config('kompass.block_types', []))),
            ],
        ];
    }

    public function call_emit_reset()
    {
        $this->dispatch('status');
    }

    protected function headerTable(): array
    {
        return ['', 'name', 'type', ''];
    }

    protected function dataTable(): array
    {
        return ['name', 'type'];
    }

    public function mount()
    {
        $this->headers = $this->headerTable();
        $this->data = $this->dataTable();
    }

    private function resultDate()
    {
        return Blocktemplates::where('name', 'like', '%'.$this->search.'%')
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->simplePaginate($this->perPage);
    }

    public function render()
    {
        return view('kompass::livewire.blocks.blocks-table', ['pages' => $this->resultDate()])->layout('kompass::admin.layouts.app');
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;
        if ($action == 'add') $this->FormAdd = true;
        if ($action == 'delete') $this->FormDelete = true;
    }

    public function saveBlock()
    {
        $validatedData = $this->validate();
        $validatedData['type'] = Str::slug($this->name);
        $block = Blocktemplates::create($validatedData);
        $this->createBlockViewFile($block->type);
        $this->reset(['name', 'type']);
        $this->FormAdd = false;
    }

    protected function createBlockViewFile(string $type): void
    {
        $path = resource_path('views/components/blocks/'.$type.'.blade.php');

        if (File::exists($path)) {
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $stub = <<<BLADE
        @props(['item' => ''])

        @php
            \$cssclassname = get_meta(\$item, 'css-classname', '');
            // \$title = get_field('title', \$item->datafield);
            // \$image = get_field('image', \$item->datafield);
            // \$text  = get_field('text',  \$item->datafield);
        @endphp

        <div class="{{ \$cssclassname }}">
            {{-- TODO: render fields from \$item->datafield --}}
        </div>
        BLADE;

        try {
            File::put($path, $stub);
        } catch (\Throwable $e) {
            Log::error('Kompass: could not create block view file', ['type' => $type, 'path' => $path, 'error' => $e->getMessage()]);
            $this->addError('type', __('Could not create block view file. Check storage permissions.'));
        }
    }

    public function confirmRegenerate(int $templateId): void
    {
        $template = Blocktemplates::findOrFail($templateId);
        $this->regenerateId = $templateId;
        $this->regenerateFileName = 'components/blocks/'.$template->type.'.blade.php';
        $this->FormRegenerate = true;
    }

    public function regenerateViewFile(int $templateId): void
    {
        $template = Blocktemplates::with('fields')->findOrFail($templateId);
        $path = resource_path('views/components/blocks/'.$template->type.'.blade.php');

        File::ensureDirectoryExists(dirname($path));

        // Count how often each type appears to detect duplicates
        $typeCounts = $template->fields->countBy('type');

        // Track per-type index for duplicate suffixes
        $typeIndex = [];

        $varMap = $template->fields->mapWithKeys(function ($field) use ($typeCounts, &$typeIndex) {
            $typeIndex[$field->type] = ($typeIndex[$field->type] ?? 0) + 1;
            $base = Str::camel($field->type);  // English type name as base
            $var = $typeCounts[$field->type] > 1
                ? $base.ucfirst(Str::camel($field->name))  // e.g. imageHeader
                : $base;                                     // e.g. image

            return [$field->name => $var];
        });

        $fieldLines = $template->fields->map(function ($field) use ($varMap) {
            $var = $varMap[$field->name];
            $raw = "\$item->datafield->firstWhere('name', '{$field->name}')?->data";

            return match ($field->type) {
                'wysiwyg' => "    \$_{$var} = wysiwyg_blocks(\$item, {$raw});",
                'link'    => "    \$_{$var}Raw = {$raw};\n    \$_{$var} = is_array(\$_{$var}Raw) ? (object) \$_{$var}Raw : (is_string(\$_{$var}Raw) ? json_decode(\$_{$var}Raw) : null);",
                'gallery' => "    \$_{$var}Raw = {$raw};\n    \$_{$var} = is_array(\$_{$var}Raw) ? \$_{$var}Raw : [];",
                default   => "    \$_{$var} = {$raw};",
            };
        })->implode("\n");

        $outputLines = $template->fields->map(function ($field) use ($varMap) {
            $var = $varMap[$field->name];

            return match ($field->type) {
                'wysiwyg'    => "    {{-- wysiwyg: {$field->name} --}}\n    @foreach (\$_{$var} as \$block)\n        <p>{!! \$block['content'] ?? '' !!}</p>\n    @endforeach",
                'image'      => "    {{-- image: {$field->name} --}}\n    @if (\$_{$var})\n        <x-image :id=\"\$_{$var}\" class=\"w-full\" />\n    @endif",
                'gallery'    => "    {{-- gallery: {$field->name} --}}\n    @foreach (\$_{$var} as \$_{$var}Id)\n        <x-image :id=\"\$_{$var}Id\" class=\"w-full\" />\n    @endforeach",
                'link'       => "    {{-- link: {$field->name} --}}\n    @if (\$_{$var})\n        <a href=\"{{ \$_{$var}->url ?? '#' }}\" class=\"underline hover:no-underline\">\n            {{ \$_{$var}->title ?? '{$field->name}' }}\n        </a>\n    @endif",
                'file'       => "    {{-- file: {$field->name} --}}\n    @if (\$_{$var})\n        @php \$_fileModel = \\Secondnetwork\\Kompass\\Models\\File::find(\$_{$var}); @endphp\n        @if (\$_fileModel)\n            <a href=\"{{ asset('storage/' . \$_fileModel->path . '/' . \$_fileModel->slug . '.' . \$_fileModel->extension) }}\" download>\n                {{ \$_fileModel->name }}\n            </a>\n        @endif\n    @endif",
                'true_false' => "    {{-- true_false: {$field->name} --}}\n    @if (\$_{$var})\n        {{-- visible when active --}}\n    @endif",
                'color'      => "    {{-- color: {$field->name} --}}\n    @if (\$_{$var})\n        <div class=\"w-8 h-8 rounded-full border border-base-300\" style=\"background-color: {{ \$_{$var} }}\"></div>\n    @endif",
                default      => "    {{-- {$field->type}: {$field->name} --}}\n    @if (\$_{$var})<p>{{ \$_{$var} }}</p>@endif",
            };
        })->implode("\n\n");

        $stub = "@props(['item' => ''])\n\n@php\n    \$cssclassname = get_meta(\$item, 'css-classname', '');\n{$fieldLines}\n@endphp\n\n<div class=\"{{ \$cssclassname }}\">\n{$outputLines}\n</div>\n";

        try {
            File::put($path, $stub);
            session()->flash('message', __('View file regenerated.'));
        } catch (\Throwable $e) {
            Log::error('Kompass: could not regenerate block view file', ['type' => $template->type, 'error' => $e->getMessage()]);
            $this->addError('type', __('Could not write view file. Check storage permissions.'));
        }
    }

    public function updatedName($value) { $this->type = Str::slug($value); }

    public function delete()
    {
        Blocktemplates::destroy($this->selectedItem);
        $this->FormDelete = false;
    }

    public function handleSort($item, $position)
    {
        $blocks = Blocktemplates::orderBy('order', 'ASC')->get();
        $movedItemIndex = $blocks->search(fn ($block) => $block->id == $item);
        if ($movedItemIndex === false) return;
        $movedItem = $blocks->pull($movedItemIndex);
        $blocks->splice($position, 0, [$movedItem]);
        foreach ($blocks->values() as $index => $block) {
            if ($block->order !== $index) $block->update(['order' => $index]);
        }
        $this->call_emit_reset();
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

    public function toJSON(): string { return '{}'; }
}
