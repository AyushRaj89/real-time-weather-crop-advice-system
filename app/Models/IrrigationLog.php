<?php
// File: app/Models/IrrigationLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IrrigationLog extends Model
{
    protected $fillable = [
        'user_id',
        'crop_plan_id',
        'field_name',
        'method',
        'water_used_liters',
        'duration_minutes',
        'irrigated_on',
        'next_reminder_set',
        'next_irrigation_date',
        'notes',
    ];

    protected $casts = [
        'irrigated_on'         => 'date',
        'next_irrigation_date' => 'date',
        'next_reminder_set'    => 'boolean',
        'water_used_liters'    => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cropPlan()
    {
        return $this->belongsTo(CropPlan::class);
    }

    /**
     * Days until next irrigation (negative = overdue).
     */
    public function getDaysUntilNextAttribute(): ?int
    {
        if (!$this->next_irrigation_date) return null;
        return (int) now()->startOfDay()->diffInDays($this->next_irrigation_date, false);
    }

    /**
     * Method icon + label.
     */
    public function getMethodLabelAttribute(): string
    {
        return match($this->method) {
            'drip'      => '💧 Drip',
            'sprinkler' => '🌀 Sprinkler',
            'flood'     => '🌊 Flood',
            'furrow'    => '〰️ Furrow',
            'manual'    => '🪣 Manual',
            default     => $this->method,
        };
    }

    /**
     * Urgency level for next irrigation.
     */
    public function getUrgencyAttribute(): string
    {
        $d = $this->days_until_next;
        if ($d === null) return 'none';
        if ($d < 0) return 'overdue';
        if ($d === 0) return 'today';
        if ($d <= 2) return 'soon';
        return 'ok';
    }
}