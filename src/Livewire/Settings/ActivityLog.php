<?php

namespace Secondnetwork\Kompass\Livewire\Settings;

use Livewire\Component;
use Livewire\WithPagination;

class ActivityLog extends Component
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
        if (!class_exists(\Spatie\Activitylog\Models\Activity::class)) {
            return view('kompass::livewire.settings.activity-log', ['logsact' => collect()]);
        }

        $logsact = \Spatie\Activitylog\Models\Activity::orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->paginate(20);

        return view('kompass::livewire.settings.activity-log', ['logsact' => $logsact]);
    }
}
