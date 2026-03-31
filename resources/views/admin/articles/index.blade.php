<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm uppercase tracking-[0.24em] text-[var(--muted)]">Admin / Konten</p>
            <h1 class="font-heading text-3xl text-[var(--coffee)]">Artikel dan berita</h1>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <livewire:admin.article-table />
        </div>
    </div>
</x-app-layout>
