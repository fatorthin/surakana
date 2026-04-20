<div>
    {{-- Notification --}}
    @if ($notification)
        <div
            class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ $notification }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[0.85fr,1.15fr]">
        {{-- Add form --}}
        <div class="surface-card space-y-5 p-6">
            <p class="eyebrow">Tambah Catatan</p>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="fin-type" value="Tipe" />
                    <select id="fin-type" wire:model="type"
                        class="mt-1 block w-full rounded-[1.25rem] border-[var(--line)] bg-white">
                        @foreach ($types as $t)
                            <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="fin-amount" value="Jumlah (Rp)" />
                    <x-text-input id="fin-amount" wire:model="amount" type="number" step="0.01"
                        class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="fin-desc" value="Deskripsi" />
                <x-text-input id="fin-desc" wire:model="description" class="mt-1 block w-full" />
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="fin-date" value="Tanggal Transaksi" />
                <x-text-input id="fin-date" wire:model="transaction_date" type="date" class="mt-1 block w-full" />
                <x-input-error :messages="$errors->get('transaction_date')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="fin-payment" value="Metode Pembayaran" />
                <select id="fin-payment" wire:model="payment_method"
                    class="mt-1 block w-full rounded-[1.25rem] border-[var(--line)] bg-white">
                    @foreach ($paymentMethods as $pm)
                        <option value="{{ $pm }}">
                            {{ match ($pm) {'cash' => 'Cash','qris' => 'QRIS / E-Wallet','transfer' => 'Transfer Bank',default => ucfirst($pm)} }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
            </div>

            <button wire:click="save" wire:loading.attr="disabled" wire:loading.class="opacity-60"
                class="btn-earth w-full justify-center">
                <span wire:loading.remove wire:target="save">Simpan Catatan</span>
                <span wire:loading wire:target="save">Menyimpan...</span>
            </button>

            <div class="grid gap-4 pt-2 sm:grid-cols-2">
                <div class="mini-stat">
                    <span>Pemasukan</span>
                    <strong>Rp{{ number_format($summary['income'], 0, ',', '.') }}</strong>
                </div>
                <div class="mini-stat">
                    <span>Pengeluaran</span>
                    <strong>Rp{{ number_format($summary['expense'], 0, ',', '.') }}</strong>
                </div>
            </div>

            <div class="border-t border-[var(--line)] pt-4">
                <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-[var(--muted)]">Pemasukan per metode
                </p>
                <div class="grid gap-3 sm:grid-cols-3">
                    @foreach ($byMethod as $method => $total)
                        <div class="mini-stat">
                            <span>{{ match ($method) {'cash' => 'Cash','qris' => 'QRIS / E-Wallet','transfer' => 'Transfer',default => ucfirst($method)} }}</span>
                            <strong>Rp{{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Ledger table --}}
        <div class="surface-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-[var(--sand)]/70 text-[var(--muted)]">
                        <tr>
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">Tipe</th>
                            <th class="px-6 py-3">Deskripsi</th>
                            <th class="px-6 py-3">Metode</th>
                            <th class="px-6 py-3">Jumlah</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($entries as $entry)
                            <tr wire:key="finance-{{ $entry->id }}" class="border-t border-[var(--line)]">
                                <td class="px-6 py-4">{{ $entry->transaction_date->translatedFormat('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="pill {{ $entry->type === 'income' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                        {{ ucfirst($entry->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $entry->description }}</td>
                                <td class="px-6 py-4">
                                    <span class="pill bg-[var(--sand)] text-[var(--coffee)]">
                                        {{ match ($entry->payment_method) {'cash' => 'Cash','qris' => 'QRIS','transfer' => 'Transfer',default => ucfirst($entry->payment_method ?? '-')} }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">Rp{{ number_format($entry->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <button wire:click="delete({{ $entry->id }})" wire:confirm="Hapus catatan ini?"
                                        wire:loading.attr="disabled" wire:target="delete({{ $entry->id }})"
                                        class="btn-danger">
                                        <span wire:loading.remove
                                            wire:target="delete({{ $entry->id }})">Hapus</span>
                                        <span wire:loading wire:target="delete({{ $entry->id }})">...</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-[var(--muted)]">Belum ada catatan keuangan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">{{ $entries->links() }}</div>
        </div>
    </div>
</div>
