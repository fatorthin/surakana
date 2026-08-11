<div>
    @if ($notification)
        <div
            class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ $notification }}
        </div>
    @endif

    {{-- Summary stats --}}
    <div class="mb-6 grid gap-4 sm:grid-cols-3">
        <div class="mini-stat">
            <span>Total Batch</span>
            <strong>{{ number_format($summary['total']) }}</strong>
        </div>
        <div class="mini-stat">
            <span>Rata Durasi</span>
            <strong>{{ floor($summary['avg_duration'] / 60) }}:{{ str_pad((string) ($summary['avg_duration'] % 60), 2, '0', STR_PAD_LEFT) }}</strong>
        </div>
        <div class="mini-stat">
            <span>Rata Susut</span>
            <strong>{{ $summary['avg_shrinkage'] !== null ? number_format($summary['avg_shrinkage'], 2) . '%' : '—' }}</strong>
        </div>
    </div>

    {{-- History table --}}
    <div class="surface-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-[var(--sand)]/70 text-[var(--muted)]">
                    <tr>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Roaster</th>
                        <th class="px-6 py-3">Bean</th>
                        <th class="px-6 py-3">Green → Roast</th>
                        <th class="px-6 py-3">Durasi</th>
                        <th class="px-6 py-3">Susut</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr wire:key="rl-{{ $log->id }}" class="border-t border-[var(--line)] align-top">
                            <td class="px-6 py-4">
                                <p class="font-medium">{{ $log->roast_date->translatedFormat('d M Y') }}</p>
                                <p class="text-xs text-[var(--muted)]">{{ $log->roast_date->format('H:i') }}</p>
                                @if ($log->origin || $log->process_method)
                                    <p class="mt-0.5 text-xs text-[var(--muted)]">
                                        {{ implode(' / ', array_filter([$log->origin, $log->process_method])) }}
                                    </p>
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $log->roaster_name }}</td>
                            <td class="px-6 py-4">
                                <p>{{ $log->bean_name }}</p>
                                @if ($log->varietas)
                                    <p class="text-xs text-[var(--muted)]">{{ $log->varietas }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                {{ number_format((float) $log->green_weight, 0, ',', '.') }}g →
                                {{ $log->roasted_weight !== null ? number_format((float) $log->roasted_weight, 0, ',', '.') . 'g' : '—' }}
                            </td>
                            <td class="px-6 py-4">{{ $log->formattedDuration() }}</td>
                            <td class="px-6 py-4">
                                {{ $log->shrinkagePercentage() !== null ? number_format($log->shrinkagePercentage(), 2) . '%' : '—' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <button wire:click="edit({{ $log->id }})"
                                        class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-1">
                                        Edit
                                    </button>
                                    <button wire:click="delete({{ $log->id }})"
                                        wire:confirm="Hapus roasting log ini?"
                                        wire:loading.attr="disabled"
                                        wire:target="delete({{ $log->id }})"
                                        class="btn-danger">
                                        <span wire:loading.remove wire:target="delete({{ $log->id }})">Hapus</span>
                                        <span wire:loading wire:target="delete({{ $log->id }})">...</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-[var(--muted)]">
                                Belum ada batch roasting. Mulai batch pertama di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">{{ $logs->links() }}</div>
    </div>

    {{-- Edit Modal --}}
    @if ($editingId)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black/50 p-4 backdrop-blur-sm">
            <div class="surface-card w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-[var(--line)] bg-[var(--coffee)] px-6 py-4 text-white">
                    <h3 class="font-heading text-lg tracking-wide">Edit Roasting Log</h3>
                    <button wire:click="cancelEdit" class="text-gray-300 hover:text-white">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="update" class="p-6 space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <x-input-label value="Nama Roaster *" />
                            <select wire:model="roaster_name" class="mt-1 block w-full border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-sm">
                                <option value="" disabled>Pilih Roaster</option>
                                @foreach (['Ndaru', 'Bhetris', 'Fathin', 'Arba'] as $name)
                                    <option value="{{ $name }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('roaster_name') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input-label value="Nama Biji *" />
                            <x-text-input wire:model="bean_name" class="mt-1 block w-full" placeholder="Gayo Aceh" />
                            @error('bean_name') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <x-input-label value="Origin" />
                            <x-text-input wire:model="origin" class="mt-1 block w-full" placeholder="Aceh, Indonesia" />
                            @error('origin') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input-label value="Varietas" />
                            <select wire:model="varietas" class="mt-1 block w-full border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-sm">
                                <option value="">Pilih Varietas</option>
                                @foreach (['Arabica', 'Robusta'] as $var)
                                    <option value="{{ $var }}">{{ $var }}</option>
                                @endforeach
                            </select>
                            @error('varietas') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input-label value="Proses" />
                            <x-text-input wire:model="process_method" class="mt-1 block w-full" placeholder="Full Wash, Natural..." />
                            @error('process_method') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <x-input-label value="Berat Green (g) *" />
                            <x-text-input type="number" step="0.01" wire:model="green_weight" class="mt-1 block w-full" />
                            @error('green_weight') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input-label value="Berat Roast (g)" />
                            <x-text-input type="number" step="0.01" wire:model="roasted_weight" class="mt-1 block w-full" />
                            @error('roasted_weight') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input-label value="Suhu Charge (°C) *" />
                            <x-text-input type="number" step="0.01" wire:model="charge_temp" class="mt-1 block w-full" />
                            @error('charge_temp') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <x-input-label value="Durasi Menit" />
                            <x-text-input type="number" min="0" wire:model="duration_minutes" class="mt-1 block w-full" placeholder="12" />
                            @error('duration_minutes') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <x-input-label value="Durasi Detik" />
                            <x-text-input type="number" min="0" max="59" wire:model="duration_seconds" class="mt-1 block w-full" placeholder="30" />
                            @error('duration_seconds') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <x-input-label value="Catatan" />
                        <textarea wire:model="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm"></textarea>
                        @error('notes') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-[var(--line)]">
                        <button type="button" wire:click="cancelEdit" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" class="btn-earth px-5 py-2 text-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
