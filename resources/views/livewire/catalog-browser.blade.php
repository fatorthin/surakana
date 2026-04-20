<div>
    {{-- Live Search Bar --}}
    <div class="surface-card mb-8 flex w-full items-center gap-3 p-3">
        <div class="relative flex-1">
            <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[var(--muted)]"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 15.803a7.5 7.5 0 0 0 10.607 0Z" />
            </svg>
            <input type="text" wire:model.live.debounce.350ms="search"
                placeholder="Cari origin, notes, atau profil..."
                class="w-full rounded-2xl border-[var(--line)] bg-white py-2 pl-11 pr-4" />
        </div>
        @if ($search)
            <button wire:click="$set('search', '')" class="btn-ghost shrink-0 px-4">Hapus</button>
        @endif
    </div>

    {{-- Loading skeleton --}}
    <div wire:loading wire:target="search" class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @foreach (range(1, 6) as $_)
            <div class="surface-card animate-pulse overflow-hidden">
                <div class="h-64 w-full rounded-[1.6rem] bg-[var(--line)]"></div>
                <div class="mt-5 space-y-3 p-1">
                    <div class="h-4 w-1/3 rounded-full bg-[var(--line)]"></div>
                    <div class="h-6 w-2/3 rounded-full bg-[var(--line)]"></div>
                    <div class="h-4 w-full rounded-full bg-[var(--line)]"></div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Product Grid --}}
    <div wire:loading.remove wire:target="search" class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($products as $product)
            <article wire:key="product-{{ $product->id }}" class="product-card">
                <img src="{{ $product->image_url ?: 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=900&q=80' }}"
                    alt="{{ $product->name }}" class="h-64 w-full rounded-[1.6rem] object-cover" />
                <div class="mt-5 flex items-start justify-between gap-3">
                    <div>
                        <div class="flex flex-wrap gap-2">
                            <span class="pill">{{ $product->roast_level }}</span>
                            <span class="pill">{{ $product->weight }}</span>
                        </div>
                        <h2 class="mt-3 font-heading text-2xl text-[var(--coffee)]">{{ $product->name }}</h2>
                    </div>
                    <p class="shrink-0 font-semibold text-[var(--coffee)]">
                        Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
                <p class="mt-3 text-sm leading-7 text-[var(--muted)]">{{ $product->description }}</p>
                <p class="mt-2 text-sm font-semibold text-[var(--forest)]">{{ $product->tasting_notes }}</p>
                <div class="mt-3 flex items-center justify-between text-xs text-[var(--muted)]">
                    <span>Stok {{ $product->stock }}</span>
                    <span>{{ $product->weight }}</span>
                </div>
                <livewire:add-to-cart :product="$product" :key="'atc-' . $product->id" />
            </article>
        @empty
            <div class="surface-card p-8 text-[var(--muted)] md:col-span-2 xl:col-span-3">
                @if ($search)
                    Tidak ada produk untuk "<strong>{{ $search }}</strong>".
                @else
                    Belum ada produk tersedia.
                @endif
            </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $products->links() }}</div>
</div>
