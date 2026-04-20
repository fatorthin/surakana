<div>
    <div class="grid gap-6 lg:grid-cols-[1.1fr,0.9fr]">
        <div class="surface-card overflow-hidden">
            @forelse ($items as $item)
                <div wire:key="cart-item-{{ $item['product_id'] }}"
                    class="flex flex-col gap-4 border-b border-[var(--line)] p-6 sm:flex-row sm:items-center">
                    <img src="{{ $item['image_url'] ?: 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=900&q=80' }}"
                        alt="{{ $item['name'] }}" class="h-28 w-full rounded-[1.3rem] object-cover sm:w-28" />

                    <div class="flex-1">
                        <h2 class="font-heading text-2xl text-[var(--coffee)]">{{ $item['name'] }}</h2>
                        <p class="text-sm text-[var(--muted)]">{{ $item['weight'] }}</p>
                        <p class="mt-2 font-semibold text-[var(--coffee)]">
                            Rp{{ number_format($item['price'], 0, ',', '.') }}</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="number" min="1" value="{{ $item['quantity'] }}"
                            wire:change="update({{ $item['product_id'] }}, $event.target.value)"
                            class="w-20 rounded-2xl border-[var(--line)] bg-white text-center" />
                        <button wire:click="remove({{ $item['product_id'] }})"
                            wire:confirm="Hapus produk ini dari keranjang?" wire:loading.attr="disabled"
                            wire:target="remove({{ $item['product_id'] }})" class="btn-danger">
                            <span wire:loading.remove wire:target="remove({{ $item['product_id'] }})">Hapus</span>
                            <span wire:loading wire:target="remove({{ $item['product_id'] }})">...</span>
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-8 text-[var(--muted)]">Keranjang masih kosong. Tambahkan kopi dari katalog.</div>
            @endforelse
        </div>

        <aside class="surface-card p-6">
            <p class="eyebrow">Ringkasan</p>
            <div class="mt-5 space-y-3 text-sm text-[var(--muted)]">
                <div class="flex items-center justify-between">
                    <span>Jumlah item</span>
                    <strong class="text-[var(--coffee)]">{{ $items->sum('quantity') }}</strong>
                </div>
                <div class="flex items-center justify-between text-lg font-semibold text-[var(--coffee)]">
                    <span>Total</span>
                    <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-6 space-y-3">
                <a href="{{ route('catalog.index') }}" class="btn-ghost w-full justify-center">Tambah produk lain</a>
                @auth
                    <a href="{{ route('checkout.show') }}" class="btn-earth w-full justify-center">Lanjut Checkout</a>
                @else
                    <a href="{{ route('login') }}" class="btn-earth w-full justify-center">Masuk untuk Checkout</a>
                @endauth
            </div>
        </aside>
    </div>
</div>
