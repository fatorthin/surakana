<div>
    {{-- Filter --}}
    <div class="surface-card mb-5 flex flex-wrap items-center gap-3 p-3">
        <select wire:model.live="filterStatus" class="rounded-2xl border-[var(--line)] bg-white text-sm">
            <option value="">Semua status</option>
            @foreach ($statuses as $s)
                <option value="{{ $s }}">{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <p class="ml-auto text-sm text-[var(--muted)]">{{ $orders->total() }} pesanan</p>
    </div>

    <div class="surface-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-[var(--sand)]/70 text-[var(--muted)]">
                    <tr>
                        <th class="px-6 py-3">Order</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr wire:key="order-{{ $order->id }}" class="border-t border-[var(--line)]">
                            <td class="px-6 py-4 font-semibold text-[var(--coffee)]">#{{ $order->id }}</td>
                            <td class="px-6 py-4">{{ $order->user->name }}</td>
                            <td class="px-6 py-4"><span class="pill">{{ ucfirst($order->status) }}</span></td>
                            <td class="px-6 py-4">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn-ghost">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-[var(--muted)]">Belum ada pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $orders->links() }}</div>
</div>
