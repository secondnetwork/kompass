<?php

namespace Secondnetwork\Kompass\Table\Views;

use Livewire\WithPagination;

abstract class DataView extends View
{
    use WithPagination;

    protected $queryString = [
        'search' => ['except' => ''],
        'filters',
        'sortBy',
        'sortOrder',
    ];

    /**
     * (Override) int Number of items to be showed,
     *
     * @var int
     */
    protected $paginate = 20;

    /** @var int Total of items found */
    public $total = 0;

    /** @var string Current query string with the search value */
    public $search;

    /** @var string Current query string with the filters value */
    public $filters = [];

    /** @var array<BaseFilter> All filters customized in the child class */
    public $filtersViews;

    /** @var array<string> All fields to search */
    public $searchBy;

    public $sortBy = null;

    public $sortOrder = 'asc';

    public $selected = [];

    public $allSelected = false;

    public function hydrate()
    {
        $this->filtersViews = $this->filters();
    }

    public function mount(QueryStringData $queryStringData)
    {
        $this->filtersViews = $this->filters();
        $this->search = $queryStringData->getSearchValue($this->search);

        $this->filters = $queryStringData->getFilterValues($this->filters);

        $this->applyDefaultFilters();

        $this->sortBy = $queryStringData->getValue('sortBy', $this->sortBy);
        $this->sortOrder = $queryStringData->getValue('sortOrder', $this->sortOrder);
    }

    /**
     * Check if each of the filters has a default value and it's not already set
     */
    public function applyDefaultFilters()
    {
        foreach ($this->filters() as $filter) {
            if (empty($this->filters[$filter->id]) && $filter->defaultValue) {
                $this->filters[$filter->id] = $filter->defaultValue;
            }
        }
    }

    /**
     * Collects all data to be passed to the view, this includes the items searched on the database
     * through the filters, this data will be passed to livewire render method
     */
    protected function getRenderData()
    {
        return [
            'items' => $this->paginatedQuery,
            'actionsByRow' => $this->actionsByRow(),
        ];
    }

    /**
     * Reset pagination
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilters()
    {
        $this->resetPage();
    }

    protected function filters()
    {
        return [];
    }

    protected function actionsByRow()
    {
        return [];
    }

    public function getActions()
    {
        return $this->actionsByRow();
    }

    /**
     * Clones the initial query (to avoid modifying it)
     * and get a model by an Id
     */
    public function getModelWhoFiredAction($id)
    {
        return (clone $this->initialQuery)->find($id);
    }

    public function updatedAllSelected($value)
    {
        $this->selected = $value ? $this->paginatedQuery->pluck('id')->map(function ($id) {
            return (string) $id;
        })->toArray() : [];
    }

    public function getInitialQDataueryProperty()
    {
        if (method_exists($this, 'repository')) {
            return $this->repository();
        }

        return $this->model::query();
    }

    /**
     * Returns the items from the database regarding to the filters selected by the user
     * applies the search query, the filters used and the total of items found
     */
    public function getQueryProperty(Searchable $searchable, Filterable $filterable, Sortable $sortable)
    {
        $query = clone $this->initialQuery;
        $query = $searchable->searchItems($query, $this->searchBy, $this->search);
        $query = $filterable->applyFilters($query, $this->filters(), $this->filters);
        $query = $sortable->sortItems($query, $this->sortBy, $this->sortOrder);

        return $query;
    }

    public function getPaginatedQueryProperty()
    {
        return $this->query->paginate($this->paginate);
    }

    /**
     * LIVEWIERE ACTIONS ARE HERE
     * All these actions are executed from the UI and those aren't for internal use
     */

    /**
     * Sets the field the table view data will be sort by
     *
     * @param  string  $field Field to sort by
     */
    public function sort($field)
    {
        if ($this->sortBy === $field) {
            $this->sortOrder = $this->sortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortOrder = 'asc';
        }
    }

    public function clearFilters()
    {
        $this->filters = [];
    }

    public function clearSearch()
    {
        $this->search = '';
    }
}
