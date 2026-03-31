<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;

class OrderDetail extends Component
{
    public int $orderId;

    public string $status = '';

    public string $notification = '';

    public function mount(Order $order): void
    {
        $this->orderId = $order->id;
        $this->status  = $order->status;
    }

    public function updateStatus(): void
    {
        $this->validate([
            'status' => ['required', 'in:' . implode(',', Order::statuses())],
        ]);

        Order::findOrFail($this->orderId)->update(['status' => $this->status]);

        $this->notification = 'Status pesanan diperbarui.';
        $this->js('setTimeout(() => $wire.set("notification", ""), 3000)');
    }

    public function render()
    {
        $order = Order::with('user', 'items.product')->findOrFail($this->orderId);

        return view('livewire.admin.order-detail', [
            'order'    => $order,
            'statuses' => Order::statuses(),
        ]);
    }
}
