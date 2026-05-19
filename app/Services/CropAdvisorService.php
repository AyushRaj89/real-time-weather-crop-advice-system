<?php


namespace App\Services;

use App\Models\CropRule;
use App\Models\WeatherLog;
use Illuminate\Support\Collection;

class CropAdvisorService
{
    /**
     * Get crop recommendations for the given weather conditions.
     * Returns a collection of matched rules with their crop data, sorted by priority.
     */
    public function getRecommendations(WeatherLog $weather): Collection
    {
        // Load all active rules with their crops (eager loading)
        $rules = CropRule::with('crop')
            ->where('is_active', true)
            ->whereHas('crop', fn($q) => $q->where('is_active', true))
            ->orderByDesc('priority')
            ->get();

        $matched = collect();
        $seenCropIds = [];

        foreach ($rules as $rule) {
            // Skip if we already matched this crop (avoid duplicates)
            if (in_array($rule->crop_id, $seenCropIds)) {
                continue;
            }

            if ($rule->matchesWeather($weather)) {
                $matched->push([
                    'crop'   => $rule->crop,
                    'rule'   => $rule,
                    'advice' => $rule->advice ?? "This crop is well suited to the current conditions.",
                ]);
                $seenCropIds[] = $rule->crop_id;
            }
        }

        // Return top 4 recommendations
        return $matched->take(4);
    }

    /**
     * Get a plain-language summary of current conditions.
     */
    public function getConditionSummary(WeatherLog $weather): string
    {
        $parts = [];

        if ($weather->temperature >= 30) {
            $parts[] = "hot temperatures ({$weather->temperature}°C)";
        } elseif ($weather->temperature <= 15) {
            $parts[] = "cool temperatures ({$weather->temperature}°C)";
        } else {
            $parts[] = "mild temperatures ({$weather->temperature}°C)";
        }

        if ($weather->humidity >= 75) {
            $parts[] = "high humidity ({$weather->humidity}%)";
        } elseif ($weather->humidity <= 40) {
            $parts[] = "low humidity ({$weather->humidity}%)";
        }

        if ($weather->rainfall > 3) {
            $parts[] = "significant rainfall ({$weather->rainfall} mm)";
        } elseif ($weather->rainfall == 0) {
            $parts[] = "no rainfall";
        }

        if (empty($parts)) {
            return "Conditions are moderate and generally favourable for most crops.";
        }

        return "Current conditions show " . implode(', ', $parts) . ".";
    }
}