<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class CartBadge extends Component
{
    public int $count = 0;

    public function mount(): void
    {
        $this->refresh();
    }

    #[On('cart-updated')]
    public function refresh(): void
    {
        $this->count = (int) collect(session('cart', []))->sum('quantity');
    }

    public function render()
    {
        return view('livewire.cart-badge');
    }
}
