<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-[0.24em] text-[var(--muted)]">Order Detail</p>
                <h1 class="font-heading text-3xl text-[var(--coffee)]">Pesanan #{{ $order->id }}</h1>
            </div>
            <span class="pill">{{ ucfirst($order->status) }}</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[1fr,0.85fr] lg:px-8">
            <div class="surface-card overflow-hidden">
                @foreach ($order->items as $item)
                    <div class="flex items-center justify-between gap-4 border-b border-[var(--line)] px-6 py-4">
                        <div>
                            <p class="font-semibold text-[var(--coffee)]">{{ $item->product->name }}</p>
                            <p class="text-sm text-[var(--muted)]">{{ $item->quantity }} x
                                Rp{{ number_format($item->price_at_time, 0, ',', '.') }}</p>
                        </div>
                        <span
                            class="font-semibold text-[var(--coffee)]">Rp{{ number_format($item->price_at_time * $item->quantity, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            <aside class="surface-card p-6">
                <p class="eyebrow">Pengiriman</p>
                <p class="mt-3 whitespace-pre-line text-sm leading-7 text-[var(--muted)]">{{ $order->shipping_address }}
                </p>
                <p class="mt-4 text-sm"><span class="font-semibold text-[var(--coffee)]">Metode:</span>
                    {{ $order->shipping_method }}</p>
                @if ($order->customer_notes)
                    <p class="mt-4 text-sm"><span class="font-semibold text-[var(--coffee)]">Catatan:</span>
                        {{ $order->customer_notes }}</p>
                @endif
                <div class="mt-6 border-t border-[var(--line)] pt-4 text-lg font-semibold text-[var(--coffee)]">
                    Total: Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>
