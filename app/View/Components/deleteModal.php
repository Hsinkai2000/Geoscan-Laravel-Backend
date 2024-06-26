<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class deleteModal extends Component
{
    public $projectid;
    /**
     * Create a new component instance.
     */
    public function __construct(
        $projectid, ) {
        $this->projectid = $projectid;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View | Closure | string
    {
        return view('components.delete-modal');
    }
}