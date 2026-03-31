<div>
    {{-- Notification toast --}}
    @if ($notification)
        <div
            class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ $notification }}
        </div>
    @endif

    {{-- Search --}}
    <div class="surface-card mb-5 flex items-center gap-3 p-3">
        <div class="relative flex-1">
            <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[var(--muted)]"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 15.803a7.5 7.5 0 0 0 10.607 0Z" />
            </svg>
            <input type="text" wire:model.live.debounce.350ms="search" placeholder="Cari nama atau roast level..."
                class="w-full rounded-2xl border-[var(--line)] bg-white py-2 pl-11 pr-4" />
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn-earth shrink-0">Tambah Produk</a>
    </div>

    <div class="surface-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-[var(--sand)]/70 text-[var(--muted)]">
                    <tr>
                        <th class="px-6 py-3">Produk</th>
                        <th class="px-6 py-3">Harga</th>
                        <th class="px-6 py-3">Stok</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr wire:key="product-{{ $product->id }}" class="border-t border-[var(--line)]">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-[var(--coffee)]">{{ $product->name }}</p>
                                <p class="text-xs text-[var(--muted)]">{{ $product->roast_level }} ·
                                    {{ $product->weight }}</p>
                            </td>
                            <td class="px-6 py-4">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">{{ $product->stock }}</td>
                            <td class="px-6 py-4">
                                <span class="pill {{ $product->is_active ? 'bg-emerald-100 text-emerald-700' : '' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn-ghost">Edit</a>
                                    <button wire:click="delete({{ $product->id }})"
                                        wire:confirm="Hapus produk &quot;{{ $product->name }}&quot;?"
                                        wire:loading.attr="disabled" wire:target="delete({{ $product->id }})"
                                        class="btn-danger">
                                        <span wire:loading.remove
                                            wire:target="delete({{ $product->id }})">Hapus</span>
                                        <span wire:loading wire:target="delete({{ $product->id }})">...</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-[var(--muted)]">
                                @if ($search)
                                    Tidak ada produk untuk "{{ $search }}".
                                @else
                                    Belum ada produk.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $products->links() }}</div>
</div>
