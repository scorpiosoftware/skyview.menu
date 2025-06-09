<?php

namespace App\Livewire;

use App\Models\Offer;
use Livewire\Attributes\On;
use Livewire\Component;

class DiningChoiceModal extends Component
{
    public $showModal = true;
    public $selectedChoice = null;
    public $selectedOffer = null;
    public $offers;
    public function mount()
    {
        $this->offers = Offer::current()->get();
    }

    #[On('open-choice')]
    public function openModal()
    {
        $this->showModal = true;
        $this->selectedChoice = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        if (session()->has('site')) {
            session()->forget('site');
        }
    }

    public function selectChoice($choice)
    {
        if (session()->has('site')) {
            session()->forget('site');
        }
        $this->selectedChoice = $choice;

        // Emit event to parent component or handle logic here
        $this->dispatch('diningChoiceSelected', $choice);
        // Close modal after selection
        $this->showModal = false;

        // Optional: Show success message
        session()->flash('message', "You selected: {$choice}");
        session()->put('site', $choice);
    }
    public function selectOffer($id)
    {
        if (session()->has('offer')) {
            session()->forget('offer');
        }
        $this->selectedOffer = $id;

        // Emit event to parent component or handle logic here
        $this->dispatch('offerSelected', $id);

        // Close modal after selection
        // $this->showModal = false;

        // Optional: Show success message
        session()->flash('message', "You selected: {$id}");
        session()->put('offer', $id);
    }
    public function render()
    {

        return view('livewire.dining-choice-modal');
    }
}
