<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeatherLog extends Model
{
    protected $fillable = [
        'user_id',
        'city',
        'country',
        'temperature',
        'feels_like',
        'humidity',
        'rainfall',
        'wind_speed',
        'weather_main',
        'weather_description',
        'weather_icon',
        'pressure',
        'visibility',
        'fetched_at',
    ];

    protected $casts = [
        'fetched_at'  => 'datetime',
        'temperature' => 'float',
        'feels_like'  => 'float',
        'rainfall'    => 'float',
        'wind_speed'  => 'float',
    ];

    /**
     * Weather log belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the weather icon URL from OpenWeatherMap.
     */
    public function getIconUrlAttribute(): string
    {
        if ($this->weather_icon) {
            return "https://openweathermap.org/img/wn/{$this->weather_icon}@2x.png";
        }
        return '';
    }

    /**
     * Determine the season based on temperature & month.
     */
    public function getSeason(): string
    {
        $month = $this->fetched_at->month;
        $temp  = $this->temperature;

        // Monsoon months (South Asia context)
        if (in_array($month, [6, 7, 8, 9]) && $this->rainfall > 0) {
            return 'Monsoon';
        }

        if ($temp < 15) return 'Winter';
        if ($temp > 30) return 'Summer';

        return 'Spring';
    }
}