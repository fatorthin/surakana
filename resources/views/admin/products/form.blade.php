<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm uppercase tracking-[0.24em] text-[var(--muted)]">Admin / Produk</p>
            <h1 class="font-heading text-3xl text-[var(--coffee)]">
                {{ isset($product) && $product->exists ? 'Edit produk' : 'Tambah produk baru' }}</h1>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <livewire:admin.product-form :product="isset($product) && $product->exists ? $product : null" />
        </div>
    </div>
</x-app-layout>
