<?php

namespace App\Livewire\Offers;

use App\Models\Category;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class OffersModal extends Component
{
    use WithFileUploads;

    public $image;
    public $name;
    public $discount;
    public $startDate;
    public $endDate;
    public $imageUrl;
    public $productsId = [];
    public $cartItems = [];
    public $active = true;
    public $tempImageUrl = null;
    public $showModal = false;
    public $products;
    public $selectedCategory;

    public $offer = null;

    #[On('openOfferModal')]
    public function showOfferModal($id = 0)
    {

        if ($id == 0) {
            $this->reset();
            $this->dispatch('reset-cart');
        }
        $this->selectedCategory = Category::first()->id;
        $this->products = Category::first()->products()->get();
        $this->offer = Offer::with('products')->find($id);
        $this->fillOfferData();
        $this->showModal = true;
    }


    public function fillOfferData()
    {
        $this->cartItems = [];
        if (!$this->offer)
            return;
        $this->imageUrl = $this->offer->image;
        $this->image = $this->offer->image;
        $this->name = $this->offer->name;
        $this->discount = $this->offer->sale_percentage;
        $this->startDate = \Carbon\Carbon::parse($this->offer->start_date)->format('Y-m-d');
        $this->endDate = \Carbon\Carbon::parse($this->offer->end_date)->format('Y-m-d');
        $this->tempImageUrl = $this->offer->image;
        $this->active = $this->offer->active;

        foreach ($this->offer->products as $item) {
            $cartItem = [
                'id' => $item->id,
                'name' => $item->name,
                'image' => $item->image,
                'price' => $item->price,
            ];
            $this->addToCart($cartItem);
        }

        $this->dispatch('append-items', $this->cartItems);
    }


    public function handleDrop($data)
    {
        $item = $data['item'];
        $zone = $data['zone'];
        $action = $data['action'];
        try {
            if ($zone === 'cart') {
                if ($action === 'add') {
                    $this->addToCart($item);
                } else if ($action === 'remove') {
                    $this->removeFromCart($item['id']);
                }
            } else if ($zone === 'favorites') {
                if ($action === 'add') {
                    $this->addToFavorites($item);
                } else if ($action === 'remove') {
                    $this->removeFromFavorites($item['id']);
                }
            }

            // Emit success event
            $this->dispatch('drop-success', [
                'message' => ucfirst($action) . 'd item ' . ($action === 'add' ? 'to' : 'from') . ' ' . $zone,
                'item' => $item,
                'zone' => $zone
            ]);
        } catch (\Exception $e) {
            // Emit error event
            $this->dispatch('drop-error', [
                'message' => 'Failed to ' . $action . ' item',
                'error' => $e->getMessage()
            ]);
        }
    }
    private function addToCart($item)
    {
        // Check if item already exists in cart
        $existingIndex = collect($this->cartItems)->search(function ($cartItem) use ($item) {
            return $cartItem['id'] == $item['id'];
        });

        if ($existingIndex === false) {
            $this->cartItems[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'image' => $item['image'],
                'price' => $item['price'] ?? 0,
                'quantity' => 1,
                'added_at' => now()
            ];
            array_push($this->productsId, $item['id']);
        }
    }
    public function removeItemByValue($value)
    {
        $this->productsId = array_filter($this->productsId, function ($item) use ($value) {
            return $item !== $value;
        });

        $this->productsId = array_values($this->productsId); // Re-index the array if needed
    }
    private function removeFromCart($itemId)
    {

        $this->removeItemByValue($itemId);
        $this->cartItems = collect($this->cartItems)->reject(function ($item) use ($itemId) {
            return $item['id'] == $itemId;
        })->values()->toArray();
    }
    public function save($id = 0)
    {
        $id = $this->offer?->id ?? 0;
        $rules = [
            'name' => 'required',
            'discount' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
            'active' => 'required',
            'productsId' => 'required',
        ];
        $rules['image'] = 'required|max:1024';
        $this->validate($rules);
        $imagePath = null;
        if ($this->image instanceof TemporaryUploadedFile) {
            $imagePath = $this->image->store('products', 'public');
        }

        $record =  Offer::updateOrCreate([
            'id' => $id,
        ], [
            'name' => $this->name,
            'sale_percentage' => $this->discount,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'active' => $this->active,
            'image' => $imagePath != null ? Storage::url(path: $imagePath) : $this->image,
        ]);

        if ($this->productsId) {
            $record->products()->sync($this->productsId);
        }


        $message = __('offer.saved');
        $this->dispatch('offer_created',  $message);
        $this->showModal = false;
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => 'image|max:1024', // max 1MB
        ]);

        $this->tempImageUrl = $this->image->temporaryUrl();
    }
    public function updatedSelectedCategory($id)
    {
        $this->products = Category::find($id)->products()->get();
        $this->dispatch('products-updated');
    }

    public function mount()
    {
        $this->reset();
        $this->selectedCategory = Category::first()->id;
        $this->products = Category::first()->products()->get();
    }
    public function render()
    {
        $categories = Category::all();
        return view('livewire.offers.offers-modal', ['categories' => $categories]);
    }
}
