<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductTable extends Component
{
    use WithPagination;

    public string $search = '';

    public string $notification = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function delete(Product $product): void
    {
        $name = $product->name;
        $product->delete();

        $this->notification = "Produk \"{$name}\" berhasil dihapus.";
        $this->js('setTimeout(() => $wire.set("notification", ""), 3000)');
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('roast_level', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.product-table', compact('products'));
    }
}
