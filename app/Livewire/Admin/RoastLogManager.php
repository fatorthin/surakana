<?php

namespace App\Livewire\Admin;

use App\Models\RoastLog;
use Livewire\Component;
use Livewire\WithPagination;

class RoastLogManager extends Component
{
    use WithPagination;

    public string $notification = '';

    public ?int $editingId = null;
    public string $roaster_name = '';
    public string $bean_name = '';
    public ?string $origin = null;
    public ?string $varietas = null;
    public ?string $process_method = null;
    public $green_weight = null;
    public $charge_temp = null;
    public $roasted_weight = null;
    public $duration_minutes = null;
    public $duration_seconds = null;
    public ?string $notes = null;

    protected function rules(): array
    {
        return [
            'roaster_name'    => 'required|string|max:255',
            'bean_name'       => 'required|string|max:255',
            'origin'          => 'nullable|string|max:255',
            'varietas'        => 'nullable|string|max:255',
            'process_method'  => 'nullable|string|max:255',
            'green_weight'    => 'required|numeric|min:0.01',
            'charge_temp'     => 'required|numeric|min:0',
            'roasted_weight'  => 'nullable|numeric|min:0',
            'duration_minutes'=> 'nullable|integer|min:0',
            'duration_seconds'=> 'nullable|integer|min:0|max:59',
            'notes'           => 'nullable|string',
        ];
    }

    public function edit(RoastLog $roastLog): void
    {
        $this->editingId = $roastLog->id;
        $this->roaster_name = $roastLog->roaster_name;
        $this->bean_name = $roastLog->bean_name;
        $this->origin = $roastLog->origin;
        $this->varietas = $roastLog->varietas;
        $this->process_method = $roastLog->process_method;
        $this->green_weight = $roastLog->green_weight;
        $this->charge_temp = $roastLog->charge_temp;
        $this->roasted_weight = $roastLog->roasted_weight;
        
        $totalSec = max(0, (int) ($roastLog->duration_seconds ?? 0));
        $this->duration_minutes = intdiv($totalSec, 60);
        $this->duration_seconds = $totalSec % 60;
        $this->notes = $roastLog->notes;
    }

    public function cancelEdit(): void
    {
        $this->reset(['editingId', 'roaster_name', 'bean_name', 'origin', 'varietas', 'process_method', 'green_weight', 'charge_temp', 'roasted_weight', 'duration_minutes', 'duration_seconds', 'notes']);
        $this->resetValidation();
    }

    public function update(): void
    {
        $this->validate();

        $roastLog = RoastLog::findOrFail($this->editingId);

        $totalDuration = null;
        if ($this->duration_minutes !== null || $this->duration_seconds !== null) {
            $totalDuration = ((int) $this->duration_minutes * 60) + (int) $this->duration_seconds;
        }

        $roastLog->update([
            'roaster_name'     => $this->roaster_name,
            'bean_name'        => $this->bean_name,
            'origin'           => $this->origin,
            'varietas'         => $this->varietas,
            'process_method'   => $this->process_method,
            'green_weight'     => $this->green_weight,
            'charge_temp'      => $this->charge_temp,
            'roasted_weight'   => $this->roasted_weight,
            'duration_seconds' => $totalDuration,
            'notes'            => $this->notes,
        ]);

        $this->cancelEdit();
        $this->notification = 'Roasting log berhasil diperbarui.';
        $this->js('setTimeout(() => $wire.set("notification", ""), 3000)');
    }

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
