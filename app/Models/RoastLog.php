<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'created_by',
    'roaster_name',
    'bean_name',
    'origin',
    'varietas',
    'process_method',
    'green_weight',
    'charge_temp',
    'roasted_weight',
    'duration_seconds',
    'checklist',
    'temp_log',
    'notes',
    'roast_date',
])]
class RoastLog extends Model
{
    use HasFactory;

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected function casts(): array
    {
        return [
            'green_weight' => 'decimal:2',
            'charge_temp' => 'decimal:2',
            'roasted_weight' => 'decimal:2',
            'duration_seconds' => 'integer',
            'checklist' => 'array',
            'temp_log' => 'array',
            'roast_date' => 'datetime',
        ];
    }

    public static function checklistStages(): array
    {
        return [
            'charge' => 'Charge',
            'turning_point' => 'Turning Point',
            'yellowing' => 'Yellowing',
            'first_crack' => 'First Crack',
            'second_crack' => 'Second Crack',
            'drop' => 'Drop',
        ];
    }

    public function shrinkagePercentage(): ?float
    {
        $green = (float) $this->green_weight;
        $roasted = $this->roasted_weight !== null ? (float) $this->roasted_weight : null;

        if (! $roasted || $green <= 0) {
            return null;
        }

        return (($green - $roasted) / $green) * 100;
    }

    public function formattedDuration(): string
    {
        $seconds = max(0, (int) ($this->duration_seconds ?? 0));
        $minutes = intdiv($seconds, 60);
        $remainder = $seconds % 60;

        return sprintf('%d:%02d', $minutes, $remainder);
    }
}
