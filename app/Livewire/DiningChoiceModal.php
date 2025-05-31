<?php

namespace App\Livewire;

use Livewire\Component;

class DiningChoiceModal extends Component
{
    public $showModal = true;
    public $selectedChoice = null;

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
    public function render()
    {

        return view('livewire.dining-choice-modal');
    }
}
