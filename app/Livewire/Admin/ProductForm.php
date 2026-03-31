<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;

class ProductForm extends Component
{
    public ?int $productId = null;

    public string $name = '';
    public string $description = '';
    public string $price = '';
    public string $stock = '';
    public string $roast_level = '';
    public string $tasting_notes = '';
    public string $weight = '250g';
    public string $image_url = '';
    public bool $is_active = true;
    public bool $is_featured = false;

    public function mount(?Product $product = null): void
    {
        if ($product && $product->exists) {
            $this->productId    = $product->id;
            $this->name         = $product->name;
            $this->description  = $product->description ?? '';
            $this->price        = (string) $product->price;
            $this->stock        = (string) $product->stock;
            $this->roast_level  = $product->roast_level ?? '';
            $this->tasting_notes = $product->tasting_notes ?? '';
            $this->weight       = $product->weight ?? '250g';
            $this->image_url    = $product->image_url ?? '';
            $this->is_active    = (bool) $product->is_active;
            $this->is_featured  = (bool) $product->is_featured;
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['required', 'string'],
            'price'         => ['required', 'numeric', 'min:0'],
            'stock'         => ['required', 'integer', 'min:0'],
            'roast_level'   => ['required', 'string', 'max:100'],
            'tasting_notes' => ['nullable', 'string', 'max:255'],
            'weight'        => ['required', 'string', 'max:50'],
            'image_url'     => ['nullable', 'url'],
        ]);

        $validated['is_active']   = $this->is_active;
        $validated['is_featured'] = $this->is_featured;

        if ($this->productId) {
            Product::findOrFail($this->productId)->update($validated);
            $message = 'Produk berhasil diperbarui.';
        } else {
            Product::query()->create($validated);
            $message = 'Produk berhasil ditambahkan.';
        }

        session()->flash('status', $message);
        $this->redirect(route('admin.products.index'));
    }

    public function render()
    {
        return view('livewire.admin.product-form');
    }
}
