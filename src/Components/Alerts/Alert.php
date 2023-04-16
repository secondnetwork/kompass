<?php

declare(strict_types=1);

namespace Secondnetwork\Kompass\Components\Alerts;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Secondnetwork\Kompass\Components\BladeComponent;

class Alert extends BladeComponent
{
    /** @var string */
    public $type;

    public function __construct(string $type = 'alert')
    {
        $this->type = $type;
    }

    public function render(): View
    {
        return view('kompass::components.alerts.alert');
    }

    public function message(): string
    {
        return (string) Arr::first($this->messages());
    }

    public function messages(): array
    {
        return (array) session()->get($this->type);
    }

    public function exists(): bool
    {
        return session()->has($this->type) && ! empty($this->messages());
    }
}
