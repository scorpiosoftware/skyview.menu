<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\On;
use Livewire\Component;

class OrderDetails extends Component
{
    public  $showModal = false;
    public $cartItems;
    public $total = 0;
    public function mount()
    {
        // Initialize any properties if needed
        $this->cartItems = Order::first()?->order ?? []; // Fetch all orders or set to an empty array
    }
    #[On('showOrder')]
    public function showOrderDetails($orderId)
    {
        // dd($orderId);
        // Logic to fetch order details by $orderId
        // For example, you might want to set a property with the order details
        $order = Order::find($orderId); // Fetch the order details or set to an empty array if not found
        $this->cartItems = $order->order ?? []; // Fetch the order details or set to an empty array if not found
        
        if ($this->cartItems) {
            $this->total = $order->total; // Assuming 'total' is a property of the Order model
        } else {
            $this->cartItems = []; // Reset to empty if no order found
            $this->total = 0; // Reset total if no order found
        }
        $this->showModal = true;
    }
    public function render()
    {
        return view('livewire.order-details');
    }
}
