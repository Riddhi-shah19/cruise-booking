<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InfoBox extends Component
{
    /**
     * Create a new component instance.
     */
    public string $icon;
    public string $title;
    public string $value;
    public string $color;

    public function __construct($icon, $title, $value, $color)
    {
        $this->icon = $icon;
        $this->title = $title;
        $this->value = $value;
        $this->color = $color;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.info-box');
    }
}
