<a href="{{ route('cart.index') }}"
    class="relative inline-flex min-h-11 min-w-11 items-center justify-center rounded-2xl border border-[var(--line)] bg-white px-4 text-sm font-semibold text-[var(--coffee)] shadow-soft transition hover:-translate-y-0.5">
    Keranjang
    @if ($count > 0)
        <span
            class="absolute -right-1 -top-1 flex h-6 min-w-6 items-center justify-center rounded-full bg-[var(--accent)] px-1 text-xs font-bold text-white">{{ $count }}</span>
    @endif
</a>
