<?php
// File: app/Models/Crop.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Crop extends Model
{
    protected $fillable = [
        'name',
        'local_name',
        'description',
        'image_url',
        'growing_season',
        'growth_days',
        'water_requirement',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * A crop has many rules that determine when it's recommended.
     */
    public function rules()
    {
        return $this->hasMany(CropRule::class);
    }

    /**
     * Only active rules.
     */
    public function activeRules()
    {
        return $this->hasMany(CropRule::class)->where('is_active', true);
    }

    /**
     * Get emoji icon based on crop name.
     */
    public function getEmojiAttribute(): string
    {
        $emojis = [
            'Rice'      => '🌾',
            'Wheat'     => '🌿',
            'Millet'    => '🌱',
            'Maize'     => '🌽',
            'Cotton'    => '☁️',
            'Mustard'   => '🌻',
            'Sugarcane' => '🎋',
            'Tomato'    => '🍅',
        ];

        return $emojis[$this->name] ?? '🌿';
    }

    /**
     * Water badge color.
     */
    public function getWaterColorAttribute(): string
    {
        return match($this->water_requirement) {
            'Low'    => 'badge-warning',
            'Medium' => 'badge-info',
            'High'   => 'badge-primary',
            default  => 'badge-secondary',
        };
    }
}