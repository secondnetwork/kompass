<?php

use Illuminate\View\Component;
use Illuminate\View\View;

class Offcanvas extends Component
{
    protected static $assets = ['alpine'];

    public function render(): View
    {
        return view('kompass::components.offcanvas');
    }
}
