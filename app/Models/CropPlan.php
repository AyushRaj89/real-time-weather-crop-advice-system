<?php
// File: app/Models/CropPlan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CropPlan extends Model
{
    protected $fillable = [
        'user_id',
        'crop_id',
        'field_name',
        'area_acres',
        'planned_sow_date',
        'expected_harvest_date',
        'status',
        'notes',
        'city',
    ];

    protected $casts = [
        'planned_sow_date'      => 'date',
        'expected_harvest_date' => 'date',
        'area_acres'            => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }

    /**
     * Days remaining until harvest (negative means overdue).
     */
    public function getDaysToHarvestAttribute(): ?int
    {
        if (!$this->expected_harvest_date) return null;
        return (int) now()->startOfDay()->diffInDays($this->expected_harvest_date, false);
    }

    /**
     * Progress percentage from sow to harvest (0–100).
     */
    public function getProgressAttribute(): int
    {
        if (!$this->expected_harvest_date || $this->status === 'planned') return 0;
        if ($this->status === 'harvested') return 100;

        $total   = $this->planned_sow_date->diffInDays($this->expected_harvest_date);
        $elapsed = $this->planned_sow_date->diffInDays(now());

        if ($total <= 0) return 100;
        return (int) min(100, max(0, ($elapsed / $total) * 100));
    }

    /**
     * Status badge color class.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'planned'   => 'status-planned',
            'sowing'    => 'status-sowing',
            'growing'   => 'status-growing',
            'harvested' => 'status-harvested',
            'failed'    => 'status-failed',
            default     => 'status-planned',
        };
    }

    /**
     * Human-readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'planned'   => '📋 Planned',
            'sowing'    => '🌱 Sowing',
            'growing'   => '🌿 Growing',
            'harvested' => '✅ Harvested',
            'failed'    => '❌ Failed',
            default     => $this->status,
        };
    }
}