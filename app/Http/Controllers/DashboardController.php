<?php


namespace App\Http\Controllers;

use App\Services\WeatherService;
use App\Services\CropAdvisorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected WeatherService $weatherService,
        protected CropAdvisorService $cropAdvisor,
    ) {}

    /**
     * Show the main dashboard.
     * Fetches weather for the user's default city (or last searched).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $city = $request->get('city', $user->default_city ?? 'London');

        // Fetch and store weather data
        $weather = $this->weatherService->fetchAndStore($city, $user);

        if (!$weather) {
            return view('dashboard.index', [
                'error'           => "Could not fetch weather for \"$city\". Please check the city name.",
                'city'            => $city,
                'weather'         => null,
                'recommendations' => collect(),
                'alerts'          => [],
                'summary'         => '',
                'history'         => collect(),
            ]);
        }

        // Get crop recommendations based on this weather
        $recommendations = $this->cropAdvisor->getRecommendations($weather);

        // Generate alerts
        $alerts = $this->weatherService->generateAlerts($weather);

        // Condition summary sentence
        $summary = $this->cropAdvisor->getConditionSummary($weather);

        // Recent weather history for this user (last 5)
        $history = $user->weatherLogs()
            ->orderByDesc('fetched_at')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'weather',
            'recommendations',
            'alerts',
            'summary',
            'history',
            'city',
        ));
    }

    /**
     * Save user's preferred default city via AJAX or form POST.
     */
    public function saveCity(Request $request)
    {
        $request->validate(['city' => 'required|string|max:100']);

        $user = Auth::user();
        $user->update(['default_city' => $request->city]);

        return redirect()->route('dashboard', ['city' => $request->city])
            ->with('success', "Default city updated to {$request->city}.");
    }
}