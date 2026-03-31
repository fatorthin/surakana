<div class="mt-5 flex items-center gap-3">
    @if ($added)
        <div
            class="flex-1 rounded-2xl border border-emerald-200 bg-emerald-50 py-2 text-center text-sm font-semibold text-emerald-700 transition-all">
            ✓ Ditambahkan ke keranjang
        </div>
    @else
        <input type="number" wire:model="quantity" min="1" max="{{ $product->stock }}"
            class="w-20 rounded-2xl border-[var(--line)] bg-white text-center" />
        <button wire:click="add" wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-not-allowed"
            class="btn-earth flex-1">
            <span wire:loading.remove wire:target="add">Tambah</span>
            <span wire:loading wire:target="add" class="flex items-center justify-center gap-1">
                <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                </svg>
                Menambahkan...
            </span>
        </button>
    @endif
</div>
