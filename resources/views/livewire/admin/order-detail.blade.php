<div>
    {{-- Notification --}}
    @if ($notification)
        <div
            class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ $notification }}
        </div>
    @endif

    {{-- Inline status updater --}}
    <div class="surface-card mb-6 flex flex-wrap items-center gap-4 p-4">
        <p class="text-sm font-semibold text-[var(--coffee)]">Update Status</p>
        <select wire:model="status" class="rounded-2xl border-[var(--line)] bg-white text-sm">
            @foreach ($statuses as $s)
                <option value="{{ $s }}">{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button wire:click="updateStatus" wire:loading.attr="disabled" wire:loading.class="opacity-60" class="btn-earth">
            <span wire:loading.remove wire:target="updateStatus">Simpan Status</span>
            <span wire:loading wire:target="updateStatus">Menyimpan...</span>
        </button>
    </div>

    {{-- Order items + sidebar --}}
    <div class="grid gap-6 lg:grid-cols-[1fr,0.85fr]">
        <div class="surface-card overflow-hidden">
            @foreach ($order->items as $item)
                <div class="flex items-center justify-between gap-4 border-b border-[var(--line)] px-6 py-4">
                    <div>
                        <p class="font-semibold text-[var(--coffee)]">{{ $item->product->name }}</p>
                        <p class="text-sm text-[var(--muted)]">{{ $item->quantity }} ×
                            Rp{{ number_format($item->price_at_time, 0, ',', '.') }}</p>
                    </div>
                    <span class="font-semibold text-[var(--coffee)]">
                        Rp{{ number_format($item->price_at_time * $item->quantity, 0, ',', '.') }}
                    </span>
                </div>
            @endforeach
        </div>

        <aside class="surface-card p-6">
            <p class="eyebrow">Customer</p>
            <p class="mt-3 font-semibold text-[var(--coffee)]">{{ $order->user->name }}</p>
            <p class="text-sm text-[var(--muted)]">{{ $order->user->email }}</p>
            <p class="mt-6 whitespace-pre-line text-sm leading-7 text-[var(--muted)]">{{ $order->shipping_address }}</p>
            <p class="mt-4 text-sm"><span class="font-semibold text-[var(--coffee)]">Metode Kirim:</span>
                {{ $order->shipping_method }}</p>
            @if ($order->payment_method)
                <p class="mt-2 text-sm"><span class="font-semibold text-[var(--coffee)]">Pembayaran:</span>
                    {{ match ($order->payment_method) {'cash' => 'Cash','qris' => 'QRIS / E-Wallet','transfer' => 'Transfer Bank',default => ucfirst($order->payment_method)} }}
                </p>
            @endif
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
