<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderTable extends Component
{
    use WithPagination;

    public string $filterStatus = '';

    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $orders = Order::query()
            ->with('user')
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(12);

        return view('livewire.admin.order-table', [
            'orders'   => $orders,
            'statuses' => Order::statuses(),
        ]);
    }
}
