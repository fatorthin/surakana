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
                                <button wire:click="delete({{ $log->id }})"
                                    wire:confirm="Hapus roasting log ini?"
                                    wire:loading.attr="disabled"
                                    wire:target="delete({{ $log->id }})"
                                    class="btn-danger">
                                    <span wire:loading.remove wire:target="delete({{ $log->id }})">Hapus</span>
                                    <span wire:loading wire:target="delete({{ $log->id }})">...</span>
                                </button>
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
</div>
