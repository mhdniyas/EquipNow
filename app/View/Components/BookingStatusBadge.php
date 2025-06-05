<?php

namespace App\View\Components;

use Illuminate\View\Component;

class BookingStatusBadge extends Component
{
    /**
     * The booking status.
     *
     * @var string
     */
    public $status;

    /**
     * Create a new component instance.
     *
     * @param  string  $status
     * @return void
     */
    public function __construct($status)
    {
        $this->status = $status;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\Support\Htmlable|\Closure|string
     */
    public function render()
    {
        return view('components.booking-status-badge');
    }
}
