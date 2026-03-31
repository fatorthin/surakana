<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class AddToCart extends Component
{
    public Product $product;

    public int $quantity = 1;

    public bool $added = false;

    public function add(): void
    {
        $this->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . $this->product->stock],
        ]);

        $cart = session()->get('cart', []);
        $existing = data_get($cart, $this->product->id . '.quantity', 0);
        $newQty = min($this->product->stock, $existing + $this->quantity);

        $cart[$this->product->id] = [
            'product_id' => $this->product->id,
            'name'       => $this->product->name,
            'price'      => (float) $this->product->price,
            'quantity'   => $newQty,
            'weight'     => $this->product->weight,
            'image_url'  => $this->product->image_url,
        ];

        session()->put('cart', $cart);

        $this->added = true;
        $this->quantity = 1;
        $this->dispatch('cart-updated');
        $this->js('setTimeout(() => $wire.resetAdded(), 1800)');
    }

    public function resetAdded(): void
    {
        $this->added = false;
    }

    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
