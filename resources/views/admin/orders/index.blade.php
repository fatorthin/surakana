<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-[0.24em] text-[var(--muted)]">Admin / Pesanan</p>
                <h1 class="font-heading text-3xl text-[var(--coffee)]">Pantau order masuk</h1>
            </div>
            <a href="{{ route('admin.orders.create') }}" class="btn-earth">+ Buat Pesanan</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <livewire:admin.order-table />
        </div>
    </div>
</x-app-layout>
