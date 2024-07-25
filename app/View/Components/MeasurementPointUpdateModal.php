<?php

namespace App\View\Components;

use App\Models\MeasurementPoint;
use App\Models\Project;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MeasurementPointUpdateModal extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Project $project, public MeasurementPoint $measurementPoint) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View | Closure | string
    {
        return view('components.measurement-point-update-modal');
    }
}