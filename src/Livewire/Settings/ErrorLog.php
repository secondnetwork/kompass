<?php

namespace Secondnetwork\Kompass\Livewire\Settings;

use Livewire\Component;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Models\ErrorLog as ErrorLogModel;


class ErrorLog extends Component
{
    use WithPagination;

    public function render()
    {
        $logsact = ErrorLogModel::orderBy('updated_at', 'desc')->paginate(20);
        return view('kompass::livewire.settings.error-log' ,['logsact' => $logsact]);
    }
}
