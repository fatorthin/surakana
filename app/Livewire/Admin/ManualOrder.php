<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ManualOrder extends Component
{
    // Customer
    public string $customerSearch = '';
    public ?int $userId = null;
    public string $userName = '';

    // Order details
    public string $shipping_address = '';
    public string $shipping_method = 'regular';
    public string $customer_notes = '';
    public string $payment_method = Order::PAYMENT_CASH;
    public string $status = Order::STATUS_PENDING;

    // Items: array of ['product_id' => int, 'product_name' => str, 'price' => float, 'quantity' => int]
    public array $items = [];

    // Product search for adding items
    public string $productSearch = '';
    public array $productResults = [];
    public array $customerResults = [];

    public string $notification = '';

    public function updatedCustomerSearch(): void
    {
        if (strlen($this->customerSearch) < 2) {
            $this->customerResults = [];
            return;
        }

        $this->customerResults = User::query()
            ->where('role', User::ROLE_CUSTOMER)
            ->where(function ($q) {
                $q->where('name', 'like', '%' . $this->customerSearch . '%')
                    ->orWhere('email', 'like', '%' . $this->customerSearch . '%');
            })
            ->limit(6)
            ->get(['id', 'name', 'email'])
            ->toArray();
    }

    public function selectCustomer(int $id, string $name): void
    {
        $this->userId = $id;
        $this->userName = $name;
        $this->customerSearch = '';
        $this->customerResults = [];
    }

    public function clearCustomer(): void
    {
        $this->userId = null;
        $this->userName = '';
        $this->customerResults = [];
    }

    public function updatedProductSearch(): void
    {
        if (strlen($this->productSearch) < 2) {
            $this->productResults = [];
            return;
        }

        $this->productResults = Product::query()
            ->where('is_active', true)
            ->where('name', 'like', '%' . $this->productSearch . '%')
            ->limit(6)
            ->get(['id', 'name', 'price', 'stock'])
            ->toArray();
    }

    public function addItem(int $productId): void
    {
        foreach ($this->items as $i => $item) {
            if ($item['product_id'] === $productId) {
                $this->items[$i]['quantity']++;
                $this->productSearch = '';
                $this->productResults = [];
                return;
            }
        }

        $product = Product::find($productId);
        if (! $product) {
            return;
        }

        $this->items[] = [
            'product_id'   => $product->id,
            'product_name' => $product->name,
            'price'        => (float) $product->price,
            'quantity'     => 1,
        ];

        $this->productSearch = '';
        $this->productResults = [];
    }

    public function removeItem(int $index): void
    {
        array_splice($this->items, $index, 1);
    }

    public function updateQuantity(int $index, int $quantity): void
    {
        if ($quantity < 1) {
            $this->removeItem($index);
            return;
        }
        $this->items[$index]['quantity'] = $quantity;
    }

    public function getTotal(): float
    {
        return array_reduce($this->items, fn($carry, $item) => $carry + ($item['price'] * $item['quantity']), 0.0);
    }

    public function save(): void
    {
        $this->validate([
            'userId'           => ['required', 'exists:users,id'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'shipping_method'  => ['required', 'string', 'max:100'],
            'payment_method'   => ['required', 'in:' . implode(',', Order::paymentMethods())],
            'status'           => ['required', 'in:' . implode(',', Order::statuses())],
            'items'            => ['required', 'array', 'min:1'],
        ]);

        DB::transaction(function () {
            $order = Order::query()->create([
                'user_id'          => $this->userId,
                'total_amount'     => $this->getTotal(),
                'status'           => $this->status,
                'shipping_address' => $this->shipping_address,
                'shipping_method'  => $this->shipping_method,
                'customer_notes'   => $this->customer_notes,
                'payment_method'   => $this->payment_method,
            ]);

            foreach ($this->items as $item) {
                OrderItem::query()->create([
                    'order_id'      => $order->id,
                    'product_id'    => $item['product_id'],
                    'quantity'      => $item['quantity'],
                    'price_at_time' => $item['price'],
                ]);
            }
        });

        $this->redirect(route('admin.orders.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.manual-order', [
            'statuses'       => Order::statuses(),
            'paymentMethods' => Order::paymentMethods(),
            'total'          => $this->getTotal(),
        ]);
    }
}
