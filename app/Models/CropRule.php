<?php
// File: app/Models/CropRule.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CropRule extends Model
{
    protected $fillable = [
        'crop_id',
        'min_temperature',
        'max_temperature',
        'min_humidity',
        'max_humidity',
        'min_rainfall',
        'max_rainfall',
        'weather_condition',
        'season',
        'priority',
        'advice',
        'is_active',
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'min_temperature' => 'float',
        'max_temperature' => 'float',
        'min_rainfall'    => 'float',
        'max_rainfall'    => 'float',
    ];

    /**
     * A rule belongs to a crop.
     */
    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }

    /**
     * Check if a given weather log matches this rule.
     */
    public function matchesWeather(WeatherLog $weather): bool
    {
        // Temperature checks
        if ($this->min_temperature !== null && $weather->temperature < $this->min_temperature) {
            return false;
        }
        if ($this->max_temperature !== null && $weather->temperature > $this->max_temperature) {
            return false;
        }

        // Humidity checks
        if ($this->min_humidity !== null && $weather->humidity < $this->min_humidity) {
            return false;
        }
        if ($this->max_humidity !== null && $weather->humidity > $this->max_humidity) {
            return false;
        }

        // Rainfall checks
        if ($this->min_rainfall !== null && $weather->rainfall < $this->min_rainfall) {
            return false;
        }
        if ($this->max_rainfall !== null && $weather->rainfall > $this->max_rainfall) {
            return false;
        }

        // Weather condition check (case-insensitive partial match)
        if ($this->weather_condition !== null) {
            if (stripos($weather->weather_main, $this->weather_condition) === false) {
                return false;
            }
        }

        // Season check
        if ($this->season !== null && $this->season !== 'All') {
            if ($weather->getSeason() !== $this->season) {
                return false;
            }
        }

        return true;
    }
}