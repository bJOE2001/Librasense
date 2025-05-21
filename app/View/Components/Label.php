<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Label extends Component
{
    /**
     * The for attribute of the label.
     *
     * @var string
     */
    public $for;

    /**
     * Create a new component instance.
     *
     * @param  string  $for
     * @return void
     */
    public function __construct($for = null)
    {
        $this->for = $for;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.label');
    }
} 