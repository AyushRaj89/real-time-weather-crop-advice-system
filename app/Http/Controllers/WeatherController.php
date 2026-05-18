    <?php
// File: app/Http/Controllers/WeatherController.php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use App\Services\CropAdvisorService;
use App\Models\WeatherLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WeatherController extends Controller
{
    public function __construct(
        protected WeatherService     $weatherService,
        protected CropAdvisorService $cropAdvisor,
    ) {}

    /**
     * Fetch fresh weather for a city and redirect back to dashboard.
     * Called by the search form on the dashboard.
     *
     * GET /weather/fetch?city=Mumbai
     */
    public function fetch(Request $request)
    {
        $request->validate([
            'city' => 'required|string|max:100',
        ]);

        $city = trim($request->get('city'));
        $user = Auth::user();

        $weather = $this->weatherService->fetchAndStore($city, $user);

        if (!$weather) {
            return redirect()->route('dashboard')
                ->withInput()
                ->with('error', "Could not fetch weather for \"$city\". Please check the city name and try again.");
        }

        return redirect()->route('dashboard', ['city' => $city])
            ->with('success', "Weather updated for {$weather->city}.");
    }

    /**
     * Return JSON weather + crop recommendations for AJAX calls.
     * Useful if you want to refresh weather without a full page reload.
     *
     * GET /weather/json?city=Delhi
     */
    public function json(Request $request)
    {
        $request->validate([
            'city' => 'required|string|max:100',
        ]);

        $city    = trim($request->get('city'));
        $user    = Auth::user();
        $weather = $this->weatherService->fetchAndStore($city, $user);

        if (!$weather) {
            return response()->json([
                'success' => false,
                'message' => "Could not fetch weather for \"$city\".",
            ], 422);
        }

        $recommendations = $this->cropAdvisor->getRecommendations($weather);
        $alerts          = $this->weatherService->generateAlerts($weather);
        $summary         = $this->cropAdvisor->getConditionSummary($weather);

        return response()->json([
            'success' => true,
            'weather' => [
                'city'                => $weather->city,
                'country'             => $weather->country,
                'temperature'         => round($weather->temperature),
                'feels_like'          => round($weather->feels_like),
                'humidity'            => $weather->humidity,
                'rainfall'            => $weather->rainfall,
                'wind_speed'          => $weather->wind_speed,
                'weather_main'        => $weather->weather_main,
                'weather_description' => $weather->weather_description,
                'icon_url'            => $weather->icon_url,
                'pressure'            => $weather->pressure,
                'season'              => $weather->getSeason(),
                'fetched_at'          => $weather->fetched_at->toDateTimeString(),
            ],
            'recommendations' => $recommendations->map(fn($item) => [
                'crop_name'         => $item['crop']->name,
                'crop_local_name'   => $item['crop']->local_name,
                'crop_emoji'        => $item['crop']->emoji,
                'growing_season'    => $item['crop']->growing_season,
                'water_requirement' => $item['crop']->water_requirement,
                'growth_days'       => $item['crop']->growth_days,
                'advice'            => $item['advice'],
            ]),
            'alerts'  => $alerts,
            'summary' => $summary,
        ]);
    }

    /**
     * Show weather history for the authenticated user.
     *
     * GET /weather/history
     */
    public function history(Request $request)
    {
        $logs = Auth::user()
            ->weatherLogs()
            ->orderByDesc('fetched_at')
            ->paginate(20);

        return view('weather.history', compact('logs'));
    }

    /**
     * Delete a specific weather log entry.
     *
     * DELETE /weather/history/{log}
     */
    public function destroyLog(WeatherLog $log)
    {
        // Ensure users can only delete their own logs
        if ($log->user_id !== Auth::id()) {
            abort(403);
        }

        $log->delete();

        return redirect()->back()
            ->with('success', 'Weather log entry removed.');
    }
}   