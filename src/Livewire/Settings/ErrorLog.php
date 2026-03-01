<?php

namespace Secondnetwork\Kompass\Livewire\Settings;

use Livewire\Component;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Models\ErrorLog as ErrorLogModel;

class ErrorLog extends Component
{
    use WithPagination;

    public $orderBy = 'updated_at';

    public $orderAsc = false;

    public function sortBy($field)
    {
        if ($this->orderBy === $field) {
            $this->orderAsc = !$this->orderAsc;
        } else {
            $this->orderBy = $field;
            $this->orderAsc = true;
        }
    }

    public function render()
    {
        $logsact = ErrorLogModel::orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->paginate(20);

        return view('kompass::livewire.settings.error-log', ['logsact' => $logsact]);
    }
}
