<div>
    {{-- Customer selector --}}
    <div class="surface-card space-y-5 p-6 mb-6">
        <p class="eyebrow">Customer</p>

        @if ($userId)
            <div
                class="flex items-center justify-between gap-4 rounded-2xl border border-[var(--line)] bg-[var(--sand)]/50 px-4 py-3">
                <span class="font-semibold text-[var(--coffee)]">{{ $userName }}</span>
                <button wire:click="clearCustomer"
                    class="text-sm text-[var(--muted)] hover:text-[var(--coffee)]">Ganti</button>
            </div>
        @else
            <div class="relative">
                <x-input-label for="cust-search" value="Cari nama / email customer" />
                <x-text-input id="cust-search" wire:model.live.debounce.300ms="customerSearch" class="mt-1 block w-full"
                    placeholder="Ketik minimal 2 huruf..." />
                @error('userId')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @if (count($customerResults) > 0)
                    <div class="absolute z-20 mt-1 w-full rounded-2xl border border-[var(--line)] bg-white shadow-lg">
                        @foreach ($customerResults as $c)
                            <button wire:click="selectCustomer({{ $c['id'] }}, '{{ addslashes($c['name']) }}')"
                                class="flex w-full flex-col px-4 py-3 text-left text-sm hover:bg-[var(--sand)]/60 first:rounded-t-2xl last:rounded-b-2xl">
                                <span class="font-semibold text-[var(--coffee)]">{{ $c['name'] }}</span>
                                <span class="text-[var(--muted)]">{{ $c['email'] }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>

    <div class="grid gap-6 lg:grid-cols-[1fr,0.75fr]">
        {{-- Items --}}
        <div class="space-y-4">
            <div class="surface-card p-6 space-y-4">
                <p class="eyebrow">Produk</p>
                @error('items')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror

                {{-- Product search --}}
                <div class="relative">
                    <x-text-input wire:model.live.debounce.300ms="productSearch" class="block w-full"
                        placeholder="Cari produk..." />

                    @if (count($productResults) > 0)
                        <div
                            class="absolute z-20 mt-1 w-full rounded-2xl border border-[var(--line)] bg-white shadow-lg">
                            @foreach ($productResults as $p)
                                <button wire:click="addItem({{ $p['id'] }})"
                                    class="flex w-full items-center justify-between gap-4 px-4 py-3 text-left text-sm hover:bg-[var(--sand)]/60 first:rounded-t-2xl last:rounded-b-2xl">
                                    <span class="font-semibold text-[var(--coffee)]">{{ $p['name'] }}</span>
                                    <span
                                        class="text-[var(--muted)]">Rp{{ number_format($p['price'], 0, ',', '.') }}</span>
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Item list --}}
                @if (count($items) > 0)
                    <div class="divide-y divide-[var(--line)]">
                        @foreach ($items as $i => $item)
                            <div wire:key="item-{{ $i }}" class="flex items-center gap-4 py-3">
                                <div class="flex-1">
                                    <p class="font-semibold text-[var(--coffee)]">{{ $item['product_name'] }}</p>
                                    <p class="text-sm text-[var(--muted)]">
                                        Rp{{ number_format($item['price'], 0, ',', '.') }} / pcs</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button
                                        wire:click="updateQuantity({{ $i }}, {{ $item['quantity'] - 1 }})"
                                        class="flex h-8 w-8 items-center justify-center rounded-full border border-[var(--line)] text-[var(--coffee)] hover:bg-[var(--sand)]">−</button>
                                    <span class="w-8 text-center font-semibold">{{ $item['quantity'] }}</span>
                                    <button
                                        wire:click="updateQuantity({{ $i }}, {{ $item['quantity'] + 1 }})"
                                        class="flex h-8 w-8 items-center justify-center rounded-full border border-[var(--line)] text-[var(--coffee)] hover:bg-[var(--sand)]">+</button>
                                </div>
                                <p class="w-28 text-right font-semibold text-[var(--coffee)]">
                                    Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                                <button wire:click="removeItem({{ $i }})"
                                    class="text-red-400 hover:text-red-600">×</button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-[var(--muted)]">Belum ada produk ditambahkan.</p>
                @endif
            </div>

            {{-- Shipping & notes --}}
            <div class="surface-card p-6 space-y-4">
                <p class="eyebrow">Pengiriman</p>
                <div>
                    <x-input-label for="ship-addr" value="Alamat Pengiriman" />
                    <textarea id="ship-addr" wire:model="shipping_address" rows="3"
                        class="mt-1 block w-full rounded-[1.25rem] border-[var(--line)] bg-white"></textarea>
                    @error('shipping_address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <x-input-label for="ship-method" value="Metode Pengiriman" />
                    <x-text-input id="ship-method" wire:model="shipping_method" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="cust-notes" value="Catatan Customer" />
                    <x-text-input id="cust-notes" wire:model="customer_notes" class="mt-1 block w-full" />
                </div>
            </div>
        </div>

        {{-- Summary sidebar --}}
        <div class="space-y-4">
            <div class="surface-card p-6 space-y-4">
                <p class="eyebrow">Ringkasan</p>

                <div>
                    <x-input-label for="pay-method" value="Metode Pembayaran" />
                    <select id="pay-method" wire:model="payment_method"
                        class="mt-1 block w-full rounded-[1.25rem] border-[var(--line)] bg-white">
                        @foreach ($paymentMethods as $pm)
                            <option value="{{ $pm }}">
                                {{ match ($pm) {'cash' => 'Cash','qris' => 'QRIS / E-Wallet','transfer' => 'Transfer Bank',default => ucfirst($pm)} }}
                            </option>
                        @endforeach
                    </select>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="ord-status" value="Status Pesanan" />
                    <select id="ord-status" wire:model="status"
                        class="mt-1 block w-full rounded-[1.25rem] border-[var(--line)] bg-white">
                        @foreach ($statuses as $s)
                            <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="border-t border-[var(--line)] pt-4">
                    <div class="flex justify-between text-sm text-[var(--muted)]">
                        <span>{{ count($items) }} item</span>
                        <span
                            class="text-lg font-semibold text-[var(--coffee)]">Rp{{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <button wire:click="save" wire:loading.attr="disabled" wire:loading.class="opacity-60"
                    class="btn-earth w-full justify-center">
                    <span wire:loading.remove wire:target="save">Buat Pesanan</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>

                <a href="{{ route('admin.orders.index') }}"
                    class="block text-center text-sm text-[var(--muted)] hover:text-[var(--coffee)]">Batal</a>
            </div>
        </div>
    </div>
</div>
