<?php

namespace Secondnetwork\Kompass\Livewire\Settings;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Component
{
    use WithPagination;

    public function render()
    {
        $logsact = Activity::orderBy('updated_at', 'desc')->paginate(20);
        return view('kompass::livewire.settings.activity-log' ,['logsact' => $logsact]);
    }
}
