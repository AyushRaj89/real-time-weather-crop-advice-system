<?php


namespace App\Services;

use App\Models\WeatherLog;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WeatherService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.openweathermap.org/data/2.5';

    public function __construct()
    {
        $this->apiKey = config('services.openweather.key', env('OPENWEATHER_API_KEY', ''));
    }

    /**
     * Fetch current weather for a city and store in DB.
     * Returns the WeatherLog model or null on failure.
     */
    public function fetchAndStore(string $city, User $user): ?WeatherLog
    {
        $data = $this->fetchFromApi($city);

        if (!$data) {
            return null;
        }

        return $this->storeWeatherLog($data, $user);
    }

    /**
     * Call OpenWeatherMap API.
     */
    public function fetchFromApi(string $city): ?array
    {
        if (empty($this->apiKey)) {
            // Return demo data when no API key is set
            return $this->getDemoData($city);
        }

        try {
            $client = Http::timeout(10)->withoutVerifying();

            $response = $client->get("{$this->baseUrl}/weather", [
                'q'     => $city,
                'appid' => $this->apiKey,
                'units' => 'metric',
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning("WeatherAPI failed for city: {$city}", [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error("WeatherAPI exception: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Parse API response and save to weather_logs.
     */
    protected function storeWeatherLog(array $data, User $user): WeatherLog
    {
        $rainfall = 0;
        if (isset($data['rain']['1h'])) {
            $rainfall = $data['rain']['1h'];
        } elseif (isset($data['rain']['3h'])) {
            $rainfall = $data['rain']['3h'] / 3;
        }

        return WeatherLog::create([
            'user_id'             => $user->id,
            'city'                => $data['name'] ?? 'Unknown',
            'country'             => $data['sys']['country'] ?? null,
            'temperature'         => $data['main']['temp'],
            'feels_like'          => $data['main']['feels_like'] ?? null,
            'humidity'            => $data['main']['humidity'],
            'rainfall'            => $rainfall,
            'wind_speed'          => $data['wind']['speed'] ?? null,
            'weather_main'        => $data['weather'][0]['main'] ?? 'Clear',
            'weather_description' => $data['weather'][0]['description'] ?? null,
            'weather_icon'        => $data['weather'][0]['icon'] ?? null,
            'pressure'            => $data['main']['pressure'] ?? null,
            'visibility'          => $data['visibility'] ?? null,
            'fetched_at'          => Carbon::now(),
        ]);
    }

    /**
     * Demo/fallback data when no API key is configured.
     * Rotates based on city name so different cities show different data.
     */
    protected function getDemoData(string $city): array
    {
        $scenarios = [
            [
                'temp' => 32, 'humidity' => 85, 'rain' => 5.2,
                'main' => 'Rain', 'desc' => 'heavy intensity rain', 'icon' => '10d',
            ],
            [
                'temp' => 18, 'humidity' => 45, 'rain' => 0,
                'main' => 'Clear', 'desc' => 'clear sky', 'icon' => '01d',
            ],
            [
                'temp' => 28, 'humidity' => 60, 'rain' => 0.5,
                'main' => 'Clouds', 'desc' => 'scattered clouds', 'icon' => '03d',
            ],
            [
                'temp' => 10, 'humidity' => 55, 'rain' => 0,
                'main' => 'Clear', 'desc' => 'clear sky', 'icon' => '01d',
            ],
        ];

        $idx = crc32(strtolower($city)) % count($scenarios);
        $idx = abs($idx);
        $s   = $scenarios[$idx];

        return [
            'name'       => ucwords($city),
            'sys'        => ['country' => 'IN'],
            'main'       => [
                'temp'       => $s['temp'],
                'feels_like' => $s['temp'] - 2,
                'humidity'   => $s['humidity'],
                'pressure'   => 1013,
            ],
            'rain'       => $s['rain'] > 0 ? ['1h' => $s['rain']] : [],
            'wind'       => ['speed' => 3.5],
            'weather'    => [[
                'main'        => $s['main'],
                'description' => $s['desc'],
                'icon'        => $s['icon'],
            ]],
            'visibility' => 10000,
        ];
    }

    /**
     * Generate weather alerts based on conditions.
     */
    public function generateAlerts(WeatherLog $weather): array
    {
        $alerts = [];

        if ($weather->rainfall > 10) {
            $alerts[] = [
                'type'    => 'danger',
                'icon'    => '🌧️',
                'message' => 'Heavy rain expected – avoid irrigation and protect harvested crops.',
            ];
        } elseif ($weather->rainfall > 3) {
            $alerts[] = [
                'type'    => 'warning',
                'icon'    => '🌦️',
                'message' => 'Moderate rainfall – check field drainage to prevent waterlogging.',
            ];
        }

        if ($weather->temperature > 40) {
            $alerts[] = [
                'type'    => 'danger',
                'icon'    => '🌡️',
                'message' => 'Extreme heat – increase irrigation frequency and protect seedlings.',
            ];
        } elseif ($weather->temperature > 35) {
            $alerts[] = [
                'type'    => 'warning',
                'icon'    => '☀️',
                'message' => 'High temperature – water crops in early morning or evening only.',
            ];
        }

        if ($weather->temperature < 5) {
            $alerts[] = [
                'type'    => 'danger',
                'icon'    => '❄️',
                'message' => 'Near-freezing temperatures – protect sensitive crops with covers.',
            ];
        }

        if ($weather->humidity > 90) {
            $alerts[] = [
                'type'    => 'warning',
                'icon'    => '💧',
                'message' => 'Very high humidity – watch for fungal diseases and mold in stored grain.',
            ];
        }

        if (isset($weather->wind_speed) && $weather->wind_speed > 15) {
            $alerts[] = [
                'type'    => 'warning',
                'icon'    => '💨',
                'message' => 'Strong winds – secure polytunnels and support tall crop stalks.',
            ];
        }

        if (empty($alerts)) {
            $alerts[] = [
                'type'    => 'success',
                'icon'    => '✅',
                'message' => 'Weather conditions are favourable. Good time for regular farm activities.',
            ];
        }

        return $alerts;
    }
}