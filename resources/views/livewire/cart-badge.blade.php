<a href="{{ route('cart.index') }}"
    class="relative inline-flex min-h-11 min-w-11 items-center justify-center rounded-2xl border border-[var(--line)] bg-white text-[var(--coffee)] shadow-soft transition hover:-translate-y-0.5"
    title="Keranjang">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
        <path
            d="M7 4V3a1 1 0 011-1h8a1 1 0 011 1v1h4a1 1 0 011 1v2a1 1 0 01-.293.707L19.414 10H20a1 1 0 011 1v8a1 1 0 01-1 1H4a1 1 0 01-1-1v-8a1 1 0 011-1h.586L2.293 7.707A1 1 0 012 7V5a1 1 0 011-1h4zm0 4h10L14 6H10l-3 2zm10 2H7v6h10v-6z" />
    </svg>
    @if ($count > 0)
        <span
            class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-[var(--accent)] text-[10px] font-bold text-white">{{ $count }}</span>
    @endif
</a>
