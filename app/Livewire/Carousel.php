<?php

namespace App\Livewire;

use App\Models\Offer;
use Livewire\Component;

class Carousel extends Component
{
    
    public function render()
    {
        $offers = Offer::current()->get();
        return view('livewire.carousel',['offers' => $offers]);
    }
}
