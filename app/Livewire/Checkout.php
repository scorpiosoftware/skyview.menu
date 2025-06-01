<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Support\Facades\App;
use Livewire\Attributes\On;
use Livewire\Component;

class Checkout extends Component
{
    public $showModal = false;
    public $lat = 33.8938;
    public $lng = 35.5018;

    public $table;
    public $address;
    public $name;
    public $phone;
    public $payment_method;
    public $note;
    #[On('locale-changed')]
    public function mount($locale = 'ar')
    {
        App::setLocale($locale);
        $this->table = Table::first()->table_number ?? '';
    }
    public function updatedAddress()
    {
        if (empty($this->address) || strlen(trim($this->address)) < 3) {
            return;
        }

        try {
            $client = new \GuzzleHttp\Client();
            $query = urlencode(trim($this->address));

            $response = $client->get("https://nominatim.openstreetmap.org/search", [
                'query' => [
                    'q' => $this->address,
                    'format' => 'json',
                    'limit' => 1,
                    'addressdetails' => 1
                ],
                'headers' => [
                    'User-Agent' => 'YourAppName/1.0 (your-email@domain.com)', // Replace with your info
                    'Accept' => 'application/json',
                ],
                'timeout' => 10,
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);

                if (!empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
                    $this->lat = (float) $data[0]['lat'];
                    $this->lng = (float) $data[0]['lon'];

                    // Emit event to update map
                    $this->emit('location-updated', $this->lat, $this->lng);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Geocoding error: ' . $e->getMessage());
        }
    }
    #[On('open-check-out')]
    public function openCheckout()
    {
        $this->showModal = true;
    }

    public function save()
    {
        $rules = [];

        if (session('site') == 'Dine_In') {
            $rules['table'] = 'required';
        } else {
            $rules['address'] = 'required';
            $rules['name'] = 'required';
            $rules['phone'] = 'required';
        }
        $this->validate($rules);
        $cartItems = Cart::with('product')
            ->where('session_id', session()->getId())
            ->get();
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        $order = Order::create([
            'table' => $this->table,
            'address' => $this->address,
            'name' => $this->name,
            'phone' => $this->phone,
            'site' => session('site'),
            'order' => $cartItems,
            'total' => $total,
            'session_id' => session()->getId(),
            'status' => 'pending',
            'note' => $this->note,
        ]);
        $this->showModal = false;
        Cart::where('session_id', $order->session_id)->delete();
        $this->dispatch('clearCart');
        $this->dispatch('toggleCart');

        if (session('site') == 'Takeaway') {
            $message = "\u{200F}طلب جديد من العميل\n\n"; // RLM character for RTL
            $message .= "👤 اسم العميل : {$order->name}\n";
            $message .= "📞 رقم الهاتف : {$order->phone}\n";
            $message .= "📍 العنوان : {$order->address}\n";
            $message .= "📝 ملاحظة : {$order->note}\n";
            $message .= "🕒 وقت الطلب : {$order->created_at->format('Y-m-d H:i:s')}\n";
            $message .= "🛒 الطلبات:\n";
            foreach ($order->order as $item) {
                $message .= "- {$item['product']['name']} (x{$item['quantity']}) - د.ع" . ($item['product']['price'] * $item['quantity']) . "\n";
            }
            $message .= "💰 المجموع : {$order->total} د.ع\n";
            $message .= "\n";
            $message .= "للاستفسار أو التعديل على الطلب، يرجى التواصل عبر الواتساب.";
            $message .= "\n\n";
            $message .= "شكرًا لاختياركم مطعمنا! سكاي فيو";
            $whatsappNumber = "9647749600060";
            $encodedMessage = urlencode($message);

            // Build WhatsApp redirect URL
            $whatsappURL = "https://wa.me/{$whatsappNumber}?text={$encodedMessage}";
            return redirect()->to($whatsappURL)->with('success', 'nice !');
        } else if (session('site') == 'Dine_In') {
            $message = "\u{200F}طلب جديد من العميل\n\n"; // RLM character for RTL
            $message .= "رقم الطاولة : {$order->table}\n";
            $message .= "🕒 وقت الطلب : {$order->created_at->format('Y-m-d H:i:s')}\n";
            $message .= "🛒 الطلبات:\n";
            foreach ($order->order as $item) {
                $message .= "- {$item['product']['name']} (x{$item['quantity']}) - د.ع" . ($item['product']['price'] * $item['quantity']) . "\n";
            }
            $message .= "💰 المجموع : {$order->total} د.ع\n";
            $message .= "\n";
            $message .= "شكرًا لاختياركم مطعمنا! سكاي فيو";
            $whatsappNumber = "9647749600060";
            $encodedMessage = urlencode($message);

            // Build WhatsApp redirect URL
            $whatsappURL = "https://wa.me/{$whatsappNumber}?text={$encodedMessage}";
            return redirect()->to($whatsappURL)->with('success', 'nice !');
        }
    }
    public function render()
    {
        $tables = \App\Models\Table::all();
        return view('livewire.checkout', compact('tables'));
    }
}
