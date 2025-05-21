<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NavLink extends Component
{
    public $active;

    /**
     * Create a new component instance.
     */
    public function __construct($active = false)
    {
        $this->active = $active;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render()
    {
        return view('components.nav-link');
    }
} 