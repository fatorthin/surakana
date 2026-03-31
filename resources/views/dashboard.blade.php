<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-[0.24em] text-[var(--muted)]">Admin Dashboard</p>
                <h2 class="font-heading text-3xl leading-tight text-[var(--coffee)]">Operasional roastery hari ini</h2>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn-earth">Tambah Produk</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                <div class="metric-card">
                    <p class="metric-label">Pending</p>
                    <p class="metric-value">{{ $summary['orders_pending'] }}</p>
                </div>
                <div class="metric-card">
                    <p class="metric-label">Processing</p>
                    <p class="metric-value">{{ $summary['orders_processing'] }}</p>
                </div>
                <div class="metric-card">
                    <p class="metric-label">Revenue</p>
                    <p class="metric-value">Rp{{ number_format($summary['revenue'], 0, ',', '.') }}</p>
                </div>
                <div class="metric-card">
                    <p class="metric-label">Expenses</p>
                    <p class="metric-value">Rp{{ number_format($summary['expenses'], 0, ',', '.') }}</p>
                </div>
                <div class="metric-card">
                    <p class="metric-label">Produk Aktif</p>
                    <p class="metric-value">{{ $summary['active_products'] }}</p>
                </div>
            </div>

            <div class="surface-card overflow-hidden">
                <div class="flex items-center justify-between border-b border-[var(--line)] px-6 py-5">
                    <div>
                        <h3 class="font-heading text-2xl text-[var(--coffee)]">Pesanan terbaru</h3>
                        <p class="text-sm text-[var(--muted)]">Prioritas roasting dan pengiriman.</p>
                    </div>
                    <a href="{{ route('admin.orders.index') }}" class="btn-ghost">Lihat semua</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-[var(--sand)]/70 text-[var(--muted)]">
                            <tr>
                                <th class="px-6 py-3">Order</th>
                                <th class="px-6 py-3">Customer</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($latestOrders as $order)
                                <tr class="border-t border-[var(--line)]">
                                    <td class="px-6 py-4 font-semibold text-[var(--coffee)]">
                                        <a href="{{ route('admin.orders.show', $order) }}">#{{ $order->id }}</a>
                                    </td>
                                    <td class="px-6 py-4">{{ $order->user->name }}</td>
                                    <td class="px-6 py-4"><span class="pill">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td class="px-6 py-4">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-[var(--muted)]">Belum ada
                                        pesanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
