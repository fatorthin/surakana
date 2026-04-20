<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.index') }}" class="btn-ghost">← Kembali</a>
            <div>
                <p class="text-sm uppercase tracking-[0.24em] text-[var(--muted)]">Admin / Pesanan</p>
                <h1 class="font-heading text-3xl text-[var(--coffee)]">Buat pesanan manual</h1>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <livewire:admin.manual-order />
        </div>
    </div>
</x-app-layout>
