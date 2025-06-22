<?php

namespace App\Livewire;

use App\Models\Offer;
use Livewire\Attributes\On;
use Livewire\Component;

class Carousel extends Component
{
    public $showModal = false;


    #[On('offerSelected')]
    public function openModal($id = null)
    {
        $this->showModal = true;
    }
    
    public function closeModal()
    {
        $this->showModal = false;

    }
    public function render()
    {
        $offers = Offer::current()->get();
        return view('livewire.carousel',['offers' => $offers]);
    }
}
