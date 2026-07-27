<?php

namespace App\Livewire\Admin;

use App\Models\RoastLog;
use Livewire\Component;
use Livewire\WithPagination;

class RoastLogManager extends Component
{
    use WithPagination;

    public string $notification = '';

    public function delete(RoastLog $roastLog): void
    {
        $roastLog->delete();

        $this->notification = 'Roasting log berhasil dihapus.';
        $this->js('setTimeout(() => $wire.set("notification", ""), 3000)');
    }

    public function render()
    {
        $logs = RoastLog::query()->latest('roast_date')->paginate(10);

        $recentLogs = RoastLog::query()->latest('roast_date')->take(50)->get();

        $avgShrinkage = $recentLogs
            ->map(fn (RoastLog $log) => $log->shrinkagePercentage())
            ->filter(fn ($v) => $v !== null)
            ->avg();

        $summary = [
            'total'         => RoastLog::query()->count(),
            'avg_duration'  => (int) round((float) RoastLog::query()->avg('duration_seconds')),
            'avg_shrinkage' => $avgShrinkage,
        ];

        return view('livewire.admin.roast-log-manager', [
            'logs'    => $logs,
            'summary' => $summary,
        ]);
    }
}
