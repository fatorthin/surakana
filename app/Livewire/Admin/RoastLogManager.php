<?php

namespace App\Livewire\Admin;

use App\Models\RoastLog;
use Livewire\Component;
use Livewire\WithPagination;

class RoastLogManager extends Component
{
    use WithPagination;

    public string $notification = '';

    // Search, Filters, Sorting & Pagination
    public string $search = '';
    public string $filterRoaster = '';
    public string $filterVarietas = '';
    public string $sortField = 'roast_date';
    public string $sortDirection = 'desc';
    public int $perPage = 10;

    // Comparison Feature
    public array $selectedIds = [];
    public bool $showCompareModal = false;

    // Edit Modal Properties
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

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterRoaster(): void
    {
        $this->resetPage();
    }

    public function updatingFilterVarietas(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function toggleSelectAll(array $pageIds): void
    {
        $stringPageIds = array_map('strval', $pageIds);
        $stringSelectedIds = array_map('strval', $this->selectedIds);

        if (count(array_intersect($stringPageIds, $stringSelectedIds)) === count($stringPageIds)) {
            $this->selectedIds = array_values(array_diff($stringSelectedIds, $stringPageIds));
        } else {
            $this->selectedIds = array_values(array_unique(array_merge($stringSelectedIds, $stringPageIds)));
        }
    }

    public function openCompare(): void
    {
        if (count($this->selectedIds) >= 2) {
            $this->showCompareModal = true;
        } else {
            $this->notification = 'Pilih minimal 2 batch untuk membandingkan.';
            $this->js('setTimeout(() => $wire.set("notification", ""), 3000)');
        }
    }

    public function closeCompare(): void
    {
        $this->showCompareModal = false;
    }

    public function clearComparison(): void
    {
        $this->selectedIds = [];
        $this->showCompareModal = false;
    }

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
        $this->selectedIds = array_values(array_diff(array_map('strval', $this->selectedIds), [(string)$roastLog->id]));

        $this->notification = 'Roasting log berhasil dihapus.';
        $this->js('setTimeout(() => $wire.set("notification", ""), 3000)');
    }

    public function render()
    {
        $query = RoastLog::query()
            ->when($this->search !== '', function ($q) {
                $q->where(function ($sub) {
                    $sub->where('bean_name', 'like', '%' . $this->search . '%')
                        ->orWhere('roaster_name', 'like', '%' . $this->search . '%')
                        ->orWhere('origin', 'like', '%' . $this->search . '%')
                        ->orWhere('process_method', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterRoaster !== '', function ($q) {
                $q->where('roaster_name', $this->filterRoaster);
            })
            ->when($this->filterVarietas !== '', function ($q) {
                $q->where('varietas', $this->filterVarietas);
            });

        $allowedSorts = ['roast_date', 'roaster_name', 'bean_name', 'green_weight', 'duration_seconds'];
        $sortField = in_array($this->sortField, $allowedSorts) ? $this->sortField : 'roast_date';
        $sortDirection = $this->sortDirection === 'asc' ? 'asc' : 'desc';

        $logs = (clone $query)->orderBy($sortField, $sortDirection)->paginate($this->perPage);

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

        $roastersList = RoastLog::query()->whereNotNull('roaster_name')->where('roaster_name', '!=', '')->distinct()->pluck('roaster_name')->filter()->values();
        $varietasList = RoastLog::query()->whereNotNull('varietas')->where('varietas', '!=', '')->distinct()->pluck('varietas')->filter()->values();

        $comparedLogs = [];
        if ($this->showCompareModal && count($this->selectedIds) >= 2) {
            $comparedLogs = RoastLog::whereIn('id', $this->selectedIds)->get();
        }

        return view('livewire.admin.roast-log-manager', [
            'logs'          => $logs,
            'summary'       => $summary,
            'roastersList'  => $roastersList,
            'varietasList'  => $varietasList,
            'comparedLogs'  => $comparedLogs,
        ]);
    }
}
