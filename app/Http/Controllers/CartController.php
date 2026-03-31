<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        return view('cart.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::query()->where('is_active', true)->findOrFail($validated['product_id']);
        $cart = $request->session()->get('cart', []);
        $existingQuantity = data_get($cart, $product->id . '.quantity', 0);
        $quantity = min($product->stock, $existingQuantity + $validated['quantity']);

        $cart[$product->id] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => (float) $product->price,
            'quantity' => $quantity,
            'weight' => $product->weight,
            'image_url' => $product->image_url,
        ];

        $request->session()->put('cart', $cart);

        return back()->with('status', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $request->session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = min($product->stock, $validated['quantity']);
            $request->session()->put('cart', $cart);
        }

        return back()->with('status', 'Keranjang diperbarui.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$product->id]);
        $request->session()->put('cart', $cart);

        return back()->with('status', 'Produk dihapus dari keranjang.');
    }

    public static function cartSnapshot(Request $request): array
    {
        $items = collect($request->session()->get('cart', []))->values();

        return [
            'count' => (int) $items->sum('quantity'),
            'total' => (float) $items->sum(fn($item) => $item['price'] * $item['quantity']),
        ];
    }

    private function cartDetails(): array
    {
        $items = collect(session('cart', []))->values();
        $total = $items->sum(fn($item) => $item['price'] * $item['quantity']);

        return [$items, $total];
    }
}
