<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart as CartModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

class Cart extends Component
{
    public Collection $cartItems;
    public float $total = 0;
    public string $sessionId;
    public bool $showModal = false;
    protected $listeners = ['toggleCart'];
    public function toggleCart()
    {
        $this->showModal = !$this->showModal;
    }
    #[On('locale-changed')]
    public function mount($locale = 'ar')
    {
        $this->sessionId = session()->getId();
        $this->loadCart();
        App::setLocale($locale);
    }

    public function loadCart()
    {
        $this->cartItems = CartModel::with('product')
            ->where('session_id', $this->sessionId)
            ->get();
        $this->calculateTotal();
    }

    #[On('addToCart')]
    public function addToCart($productId)
    {
        $cartItem = CartModel::where('session_id', $this->sessionId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            CartModel::create([
                'session_id' => $this->sessionId,
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }
        $this->dispatch('cartUpdated',__('alert.cart_item_added'));
        $this->loadCart();
    }

    public function removeFromCart($cartItemId)
    {
        CartModel::where('id', $cartItemId)->delete();
        $this->loadCart();
                $this->dispatch('cartUpdated',__('alert.cart_item_removed'));
    }

    public function updateQuantity($cartItemId, $quantity)
    {
        if ($quantity > 0) {
            CartModel::where('id', $cartItemId)->update(['quantity' => $quantity]);
        } else {
            $this->removeFromCart($cartItemId);
        }
        $this->loadCart();
    }

    private function calculateTotal()
    {
        $this->total = $this->cartItems->sum(function ($item) {
            return $item->quantity * $item->product->getDiscountedPriceAttribute();
        });
    }

    public function render()
    {
        return view('livewire.cart');
    }
}
