<?php

namespace App\View\Components;

use App\Models\Project;
use Illuminate\View\Component;

class projectUpdateModal extends Component
{
    /**
     * Create a new component instance.
     *
     * @param  string  $job_number
     * @return void
     */
    public function __construct(
        public Project $project, ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.project.project-update-modal');
    }
}