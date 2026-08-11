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

    {{-- Filter toolbar & comparison action --}}
    <div class="mb-5 flex flex-col gap-4 rounded-2xl border border-[var(--line)] bg-white p-5 shadow-sm lg:flex-row lg:items-center lg:justify-between">
        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
            {{-- Search Input --}}
            <div class="relative min-w-[240px] flex-1 sm:flex-initial">
                <input type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari biji, roaster, origin..."
                    class="w-full rounded-xl border border-gray-200 bg-gray-50/50 pl-10 pr-4 py-2 text-sm transition focus:border-[var(--coffee)] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[var(--coffee)]" />
                <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            {{-- Filter Roaster --}}
            <div class="shrink-0">
                <select wire:model.live="filterRoaster" class="w-full min-w-[150px] rounded-xl border border-gray-200 bg-gray-50/50 px-3.5 py-2 text-sm text-gray-700 focus:border-[var(--coffee)] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[var(--coffee)]">
                    <option value="">Semua Roaster</option>
                    @foreach ($roastersList as $rName)
                        <option value="{{ $rName }}">{{ $rName }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Varietas --}}
            <div class="shrink-0">
                <select wire:model.live="filterVarietas" class="w-full min-w-[150px] rounded-xl border border-gray-200 bg-gray-50/50 px-3.5 py-2 text-sm text-gray-700 focus:border-[var(--coffee)] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[var(--coffee)]">
                    <option value="">Semua Varietas</option>
                    @foreach ($varietasList as $vName)
                        <option value="{{ $vName }}">{{ $vName }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Per Page Dropdown --}}
            <div class="flex items-center gap-2 text-xs text-[var(--muted)] shrink-0">
                <span class="font-medium whitespace-nowrap">Tampilkan:</span>
                <select wire:model.live="perPage" class="w-full min-w-[150px] rounded-lg border border-gray-200 bg-gray-50/50 px-2.5 py-1.5 text-xs text-gray-700 focus:border-[var(--coffee)] focus:bg-white focus:outline-none">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

        {{-- Compare Trigger Button --}}
        <div class="flex items-center gap-3 shrink-0">
            @if (count($selectedIds) > 0)
                <span class="text-xs font-semibold text-amber-800 bg-amber-100/80 px-2.5 py-1 rounded-full">{{ count($selectedIds) }} dipilih</span>
                <button wire:click="clearComparison" class="text-xs font-medium text-red-600 hover:underline">
                    Batal
                </button>
            @endif
            <button wire:click="openCompare"
                class="btn-earth flex items-center gap-2 text-xs py-2.5 px-4 shadow-sm font-semibold rounded-xl transition {{ count($selectedIds) < 2 ? 'opacity-50 cursor-not-allowed' : 'hover:scale-[1.02]' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Bandingkan Data ({{ count($selectedIds) }})
            </button>
        </div>
    </div>

    {{-- History table --}}
    <div class="surface-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-[var(--sand)]/70 text-[var(--muted)]">
                    <tr>
                        <th class="px-5 py-3.5 w-12 text-center">
                            @php
                                $pageIds = array_map('strval', $logs->pluck('id')->toArray());
                                $stringSelected = array_map('strval', $selectedIds);
                                $isAllChecked = count($pageIds) > 0 && count(array_intersect($pageIds, $stringSelected)) === count($pageIds);
                            @endphp
                            <input type="checkbox"
                                wire:click="toggleSelectAll({{ json_encode($logs->pluck('id')->toArray()) }})"
                                {{ $isAllChecked ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-gray-300 text-[var(--coffee)] focus:ring-[var(--coffee)] cursor-pointer">
                        </th>
                        <th class="px-4 py-3.5 font-semibold cursor-pointer select-none" wire:click="sortBy('roast_date')">
                            <div class="flex items-center gap-1">
                                Tanggal
                                @if($sortField === 'roast_date')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3.5 font-semibold cursor-pointer select-none" wire:click="sortBy('roaster_name')">
                            <div class="flex items-center gap-1">
                                Roaster
                                @if($sortField === 'roaster_name')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3.5 font-semibold cursor-pointer select-none" wire:click="sortBy('bean_name')">
                            <div class="flex items-center gap-1">
                                Bean
                                @if($sortField === 'bean_name')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3.5 font-semibold cursor-pointer select-none" wire:click="sortBy('green_weight')">
                            <div class="flex items-center gap-1">
                                Green → Roast
                                @if($sortField === 'green_weight')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3.5 font-semibold cursor-pointer select-none" wire:click="sortBy('duration_seconds')">
                            <div class="flex items-center gap-1">
                                Durasi
                                @if($sortField === 'duration_seconds')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3.5 font-semibold">Susut</th>
                        <th class="px-6 py-3.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--line)]">
                    @forelse ($logs as $log)
                        @php
                            $isRowSelected = in_array((string)$log->id, array_map('strval', $selectedIds), true);
                        @endphp
                        <tr wire:key="rl-{{ $log->id }}" class="transition hover:bg-amber-50/30 align-top {{ $isRowSelected ? 'bg-amber-50/70' : '' }}">
                            <td class="px-5 py-4 text-center">
                                <input type="checkbox" wire:model.live="selectedIds" value="{{ (string)$log->id }}" class="h-4 w-4 rounded border-gray-300 text-[var(--coffee)] focus:ring-[var(--coffee)] cursor-pointer">
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-medium text-gray-900">{{ $log->roast_date->translatedFormat('d M Y') }}</p>
                                <p class="text-xs text-[var(--muted)] font-mono">{{ $log->roast_date->format('H:i') }}</p>
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
                            <td colspan="8" class="px-6 py-10 text-center text-[var(--muted)]">
                                Belum ada batch roasting yang cocok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-[var(--line)]">{{ $logs->links() }}</div>
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

    {{-- Comparison Modal --}}
    @if ($showCompareModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black/50 p-4 backdrop-blur-sm">
            <div class="surface-card w-full max-w-5xl overflow-hidden rounded-2xl bg-white shadow-2xl">
                <div class="flex items-center justify-between border-b border-[var(--line)] bg-[var(--coffee)] px-6 py-4 text-white">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <h3 class="font-heading text-lg tracking-wide">Perbandingan Data Roasting Log</h3>
                    </div>
                    <button wire:click="closeCompare" class="text-gray-300 hover:text-white">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6 overflow-x-auto max-h-[75vh]">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="py-3 px-4 bg-gray-50 text-[var(--muted)] font-semibold w-40">Parameter</th>
                                @foreach ($comparedLogs as $cLog)
                                    <th class="py-3 px-4 font-bold text-[var(--coffee)] border-l border-gray-100 min-w-[200px]">
                                        Batch #{{ $cLog->id }} - {{ $cLog->bean_name }}
                                        <span class="block text-xs font-normal text-[var(--muted)]">{{ $cLog->roast_date->format('d M Y, H:i') }}</span>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="py-3 px-4 font-medium bg-gray-50 text-gray-700">Roaster</td>
                                @foreach ($comparedLogs as $cLog)
                                    <td class="py-3 px-4 border-l border-gray-100">{{ $cLog->roaster_name }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="py-3 px-4 font-medium bg-gray-50 text-gray-700">Origin / Process</td>
                                @foreach ($comparedLogs as $cLog)
                                    <td class="py-3 px-4 border-l border-gray-100">
                                        {{ implode(' / ', array_filter([$cLog->origin, $cLog->varietas, $cLog->process_method])) ?: '—' }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="py-3 px-4 font-medium bg-gray-50 text-gray-700">Berat Green</td>
                                @foreach ($comparedLogs as $cLog)
                                    <td class="py-3 px-4 border-l border-gray-100 font-semibold">
                                        {{ number_format((float) $cLog->green_weight, 0, ',', '.') }} g
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="py-3 px-4 font-medium bg-gray-50 text-gray-700">Berat Roasted</td>
                                @foreach ($comparedLogs as $cLog)
                                    <td class="py-3 px-4 border-l border-gray-100 font-semibold">
                                        {{ $cLog->roasted_weight !== null ? number_format((float) $cLog->roasted_weight, 0, ',', '.') . ' g' : '—' }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="py-3 px-4 font-medium bg-gray-50 text-gray-700">Susut (%)</td>
                                @foreach ($comparedLogs as $cLog)
                                    <td class="py-3 px-4 border-l border-gray-100 font-bold text-amber-700">
                                        {{ $cLog->shrinkagePercentage() !== null ? number_format($cLog->shrinkagePercentage(), 2) . '%' : '—' }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="py-3 px-4 font-medium bg-gray-50 text-gray-700">Suhu Charge</td>
                                @foreach ($comparedLogs as $cLog)
                                    <td class="py-3 px-4 border-l border-gray-100">
                                        {{ number_format((float) $cLog->charge_temp, 1) }} °C
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="py-3 px-4 font-medium bg-gray-50 text-gray-700">Durasi Total</td>
                                @foreach ($comparedLogs as $cLog)
                                    <td class="py-3 px-4 border-l border-gray-100 font-medium">
                                        {{ $cLog->formattedDuration() }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="py-3 px-4 font-medium bg-gray-50 text-gray-700">Checklist Tahapan</td>
                                @foreach ($comparedLogs as $cLog)
                                    <td class="py-3 px-4 border-l border-gray-100 text-xs">
                                        @if (is_array($cLog->checklist) && count($cLog->checklist) > 0)
                                            <ul class="space-y-1">
                                                @foreach ($cLog->checklist as $stage => $sec)
                                                    <li>
                                                        <span class="font-medium text-gray-700">{{ \App\Models\RoastLog::checklistStages()[$stage] ?? $stage }}:</span>
                                                        <span class="text-amber-800">{{ floor((int)$sec / 60) }}:{{ str_pad((string)((int)$sec % 60), 2, '0', STR_PAD_LEFT) }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-[var(--muted)]">—</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="py-3 px-4 font-medium bg-gray-50 text-gray-700">Catatan</td>
                                @foreach ($comparedLogs as $cLog)
                                    <td class="py-3 px-4 border-l border-gray-100 text-xs text-gray-600 italic">
                                        {{ $cLog->notes ?: '—' }}
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end gap-3 p-4 border-t border-[var(--line)] bg-gray-50">
                    <button wire:click="closeCompare" class="btn-earth px-5 py-2 text-sm">
                        Tutup Perbandingan
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
