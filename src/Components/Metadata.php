<?php

use Illuminate\View\Component;
use Illuminate\View\View;

class Metadata extends Component
{
    public string $pagename;

    public string $groupname;

    public function __construct(string $pagename, ?string $groupname = null)
    {
        $this->pagename = $pagename;
        $this->dismissible = $groupname;
    }

    public function render(): View
    {
        return view('x-kompass::metadata');
    }
}
