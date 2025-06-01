<?php

namespace App\Livewire\Orders;

use App\Http\Export\OrderExport;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;

class OrdersTable extends Component
{
    use WithPagination;
    // public $orders;


    public $showOrderDetails = false;
    public $selectedOrderId = null;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $statusFilter = '';
    public function mount()
    {
        // $this->orders = Order::all();
    }
    public function showOrderModal($orderId)
    {
        $this->selectedOrderId = $orderId;
        $this->showOrderDetails = true;
        $this->dispatch('showOrder',  $orderId);
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    public function updateStatus($orderId, $status)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->status = $status;
            $order->save();
            // $this->orders = Order::all(); // Refresh data
        }
        // Order::find($orderId)?->update(['status' => $status]);
    }

    public function deleteOrder($orderId)
    {
        Order::find($orderId)?->delete();
    }
    public function render()
    {
        $orders = Order::query()
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%')
                    ->orWhere('address', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('livewire.orders.orders-table', compact('orders'));
    }
}
