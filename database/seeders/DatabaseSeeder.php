<?php
// File: database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Crop;
use App\Models\CropRule;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create or update admin user
        User::updateOrCreate(
            ['email' => 'admin@cropweather.com'],
            [
                'name'         => 'Admin User',
                'password'     => Hash::make('password'),
                'default_city' => 'Punjab',
                'is_admin'     => true,
            ]
        );

        // Create or update demo user
        User::updateOrCreate(
            ['email' => 'demo@cropweather.com'],
            [
                'name'         => 'Demo Farmer',
                'password'     => Hash::make('password'),
                'default_city' => 'Mumbai',
                'is_admin'     => false,
            ]
        );

        // Seed crops
        $crops = [
            [
                'name'             => 'Rice',
                'local_name'       => 'Paddy / Chawal',
                'description'      => 'Staple crop requiring high water and humidity. Best grown in monsoon season.',
                'growing_season'   => 'Kharif',
                'growth_days'      => 120,
                'water_requirement'=> 'High',
            ],
            [
                'name'             => 'Wheat',
                'local_name'       => 'Gehun',
                'description'      => 'Cool weather crop grown in winter. Requires moderate rainfall.',
                'growing_season'   => 'Rabi',
                'growth_days'      => 150,
                'water_requirement'=> 'Medium',
            ],
            [
                'name'             => 'Millet',
                'local_name'       => 'Bajra / Jowar',
                'description'      => 'Drought-resistant crop ideal for low rainfall and semi-arid conditions.',
                'growing_season'   => 'Kharif',
                'growth_days'      => 90,
                'water_requirement'=> 'Low',
            ],
            [
                'name'             => 'Maize',
                'local_name'       => 'Makka / Corn',
                'description'      => 'Versatile crop that does well in moderate temperature and rainfall.',
                'growing_season'   => 'All',
                'growth_days'      => 100,
                'water_requirement'=> 'Medium',
            ],
            [
                'name'             => 'Cotton',
                'local_name'       => 'Kapas',
                'description'      => 'Warm weather crop needing long dry spells with moderate early rainfall.',
                'growing_season'   => 'Kharif',
                'growth_days'      => 180,
                'water_requirement'=> 'Medium',
            ],
            [
                'name'             => 'Mustard',
                'local_name'       => 'Sarson',
                'description'      => 'Cool season oilseed crop. Grows well in dry winter conditions.',
                'growing_season'   => 'Rabi',
                'growth_days'      => 100,
                'water_requirement'=> 'Low',
            ],
            [
                'name'             => 'Sugarcane',
                'local_name'       => 'Ganna',
                'description'      => 'Tropical crop needing hot, humid climate and abundant water.',
                'growing_season'   => 'All',
                'growth_days'      => 365,
                'water_requirement'=> 'High',
            ],
            [
                'name'             => 'Tomato',
                'local_name'       => 'Tamatar',
                'description'      => 'Warm season vegetable. Needs moderate temperatures and consistent moisture.',
                'growing_season'   => 'All',
                'growth_days'      => 75,
                'water_requirement'=> 'Medium',
            ],
        ];

        foreach ($crops as $cropData) {
            Crop::create($cropData);
        }

        // Seed crop rules
        $rules = [
            // Rice: high humidity + rain
            [
                'crop_id'           => 1,
                'min_humidity'      => 70,
                'max_humidity'      => 100,
                'min_rainfall'      => 2,
                'weather_condition' => 'Rain',
                'season'            => 'Monsoon',
                'priority'          => 10,
                'advice'            => 'High humidity and rainfall detected – ideal conditions for rice cultivation.',
            ],
            // Rice: just high humidity
            [
                'crop_id'           => 1,
                'min_humidity'      => 75,
                'max_temperature'   => 35,
                'min_temperature'   => 20,
                'priority'          => 8,
                'advice'            => 'Humid warm conditions favor rice growth. Ensure standing water availability.',
            ],
            // Wheat: cold and dry
            [
                'crop_id'           => 2,
                'min_temperature'   => 5,
                'max_temperature'   => 20,
                'max_humidity'      => 65,
                'max_rainfall'      => 1,
                'season'            => 'Winter',
                'priority'          => 9,
                'advice'            => 'Cool dry weather is perfect for wheat sowing. Irrigate every 2 weeks.',
            ],
            // Millet: low rainfall, hot
            [
                'crop_id'           => 3,
                'min_temperature'   => 25,
                'max_rainfall'      => 0.5,
                'max_humidity'      => 55,
                'priority'          => 9,
                'advice'            => 'Low rainfall and dry conditions – millet is your best drought-resistant option.',
            ],
            // Maize: moderate everything
            [
                'crop_id'           => 4,
                'min_temperature'   => 18,
                'max_temperature'   => 32,
                'min_humidity'      => 40,
                'max_humidity'      => 75,
                'priority'          => 7,
                'advice'            => 'Balanced temperature and humidity – maize will thrive. Space rows 60cm apart.',
            ],
            // Cotton: hot, moderate rain
            [
                'crop_id'           => 5,
                'min_temperature'   => 25,
                'max_temperature'   => 40,
                'max_humidity'      => 60,
                'weather_condition' => 'Clear',
                'priority'          => 8,
                'advice'            => 'Hot clear weather is ideal for cotton. Avoid waterlogging.',
            ],
            // Mustard: cool and dry
            [
                'crop_id'           => 6,
                'min_temperature'   => 5,
                'max_temperature'   => 18,
                'max_humidity'      => 60,
                'max_rainfall'      => 0.5,
                'priority'          => 8,
                'advice'            => 'Dry cool weather suits mustard. Minimal irrigation needed.',
            ],
            // Sugarcane: hot and humid
            [
                'crop_id'           => 7,
                'min_temperature'   => 27,
                'min_humidity'      => 60,
                'priority'          => 6,
                'advice'            => 'Hot humid conditions support sugarcane growth. Ensure drainage channels.',
            ],
            // Tomato: mild conditions
            [
                'crop_id'           => 8,
                'min_temperature'   => 15,
                'max_temperature'   => 28,
                'min_humidity'      => 50,
                'max_humidity'      => 75,
                'priority'          => 7,
                'advice'            => 'Mild conditions are great for tomatoes. Support plants with stakes.',
            ],
        ];

        foreach ($rules as $rule) {
            CropRule::create($rule);
        }
    }
}