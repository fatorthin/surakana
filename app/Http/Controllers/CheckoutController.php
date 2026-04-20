<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        $items = collect($request->session()->get('cart', []))->values();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('status', 'Keranjang masih kosong.');
        }

        $total = $items->sum(fn($item) => $item['price'] * $item['quantity']);

        return view('checkout.show', compact('items', 'total'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shipping_address' => ['required', 'string', 'min:10'],
            'shipping_method' => ['required', 'string', 'max:50'],
            'customer_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $cart = collect($request->session()->get('cart', []));

        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->with('status', 'Keranjang masih kosong.');
        }

        DB::transaction(function () use ($cart, $request, $validated): void {
            $products = Product::query()
                ->whereIn('id', $cart->pluck('product_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $total = 0;

            foreach ($cart as $cartItem) {
                $product = $products[$cartItem['product_id']] ?? null;

                abort_if(! $product || $product->stock < $cartItem['quantity'], 422, 'Stok tidak cukup untuk checkout.');

                $total += $cartItem['price'] * $cartItem['quantity'];
            }

            $order = Order::query()->create([
                'user_id' => $request->user()->id,
                'total_amount' => $total,
                'status' => Order::STATUS_PENDING,
                'shipping_address' => $validated['shipping_address'],
                'shipping_method' => $validated['shipping_method'],
                'customer_notes' => $validated['customer_notes'] ?? null,
            ]);

            foreach ($cart as $cartItem) {
                $product = $products[$cartItem['product_id']];

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $cartItem['quantity'],
                    'price_at_time' => $cartItem['price'],
                ]);

                $product->decrement('stock', $cartItem['quantity']);
            }

            Finance::query()->create([
                'type' => Finance::TYPE_INCOME,
                'amount' => $total,
                'description' => 'Pesanan #' . $order->id,
                'transaction_date' => now()->toDateString(),
            ]);
        });

        $request->session()->forget('cart');

        return redirect()->route('orders.index')->with('status', 'Pesanan berhasil dibuat dan menunggu konfirmasi.');
    }
}
