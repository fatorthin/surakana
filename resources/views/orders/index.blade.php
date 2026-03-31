<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm uppercase tracking-[0.24em] text-[var(--muted)]">Customer Portal</p>
            <h1 class="font-heading text-3xl text-[var(--coffee)]">Riwayat pesanan</h1>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="surface-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-[var(--sand)]/70 text-[var(--muted)]">
                            <tr>
                                <th class="px-6 py-3">Order</th>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Total</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr class="border-t border-[var(--line)]">
                                    <td class="px-6 py-4 font-semibold text-[var(--coffee)]">#{{ $order->id }}</td>
                                    <td class="px-6 py-4">{{ $order->created_at->translatedFormat('d M Y') }}</td>
                                    <td class="px-6 py-4"><span class="pill">{{ ucfirst($order->status) }}</span></td>
                                    <td class="px-6 py-4">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4"><a class="text-sm font-semibold text-[var(--accent-deep)]"
                                            href="{{ route('orders.show', $order) }}">Detail</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-[var(--muted)]">Belum ada
                                        pesanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8">{{ $orders->links() }}</div>
        </div>
    </div>
</x-app-layout>
