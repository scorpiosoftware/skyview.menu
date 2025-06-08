<?php

namespace App\Livewire\Offers;

use App\Models\Offer;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class OffersTable extends Component
{

    use WithPagination;
    public $search;

    public function showOfferModal($id = 0)
    {
        $this->dispatch('openOfferModal',$id);
    }
    public function delete($id)
    {
        Offer::find($id)->delete();
    }

    public function render()
    {
        $offers =  Offer::paginate(10);
        return view('livewire.offers.offers-table', compact('offers'));
    }
}
