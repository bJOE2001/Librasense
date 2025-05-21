<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Dropdown extends Component
{
    public $align;
    public $width;
    public $contentClasses;

    /**
     * Create a new component instance.
     */
    public function __construct($align = 'right', $width = '48', $contentClasses = '')
    {
        $this->align = $align;
        $this->width = $width;
        $this->contentClasses = $contentClasses;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render()
    {
        return view('components.dropdown');
    }
} 