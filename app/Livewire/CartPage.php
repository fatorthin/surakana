<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Support\Collection;
use Livewire\Component;

class CartPage extends Component
{
    public Collection $items;

    public float $total = 0.0;

    public function mount(): void
    {
        $this->items = collect();
        $this->loadCart();
    }

    public function update(int $productId, int $quantity): void
    {
        $product = Product::find($productId);
        if ($product) {
            $cart = session()->get('cart', []);
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = min($product->stock, max(1, $quantity));
                session()->put('cart', $cart);
            }
        }

        $this->loadCart();
        $this->dispatch('cart-updated');
    }

    public function remove(int $productId): void
    {
        $cart = session()->get('cart', []);
        unset($cart[$productId]);
        session()->put('cart', $cart);

        $this->loadCart();
        $this->dispatch('cart-updated');
    }

    private function loadCart(): void
    {
        $this->items = collect(session('cart', []))->values();
        $this->total = (float) $this->items->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}
