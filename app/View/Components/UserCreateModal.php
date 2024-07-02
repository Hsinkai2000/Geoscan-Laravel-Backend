<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserCreateModal extends Component
{
    public string $prevModal;
    /**
     * Create a new component instance.
     *
     * @param  string  $job_number
     * @return void
     */
    public function __construct(string $prevModal)
    {
        $this->prevModal = $prevModal;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View | Closure | string
    {
        return view('components.user.user-create-modal');
    }
}