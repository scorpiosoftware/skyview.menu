<?php

namespace App\Livewire;

use Livewire\Component;

class Product extends Component
{

    public $product;
    public function mount($product){

    }
    public function render()
    {
        return view('livewire.product');
    }
}
