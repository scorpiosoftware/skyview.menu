<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class CountdownTimer extends Component
{
    public $targetDate; // Set this in the mount method or pass as prop

    public function mount($targetDate = null)
    {
        $this->targetDate = $targetDate ?? Carbon::now()->addHours(2)->toDateTimeString(); // fallback
    }
    public function render()
    {
        return view('livewire.countdown-timer');
    }
}
