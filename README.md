# 🌾 CropCast — Real-Time Weather & Crop Advisory System

> A Laravel 11 full-stack web application that fetches live weather data and recommends the best crops to grow based on current conditions — with smart farm alerts and a full admin panel.

---

## ✨ Features

### 👨‍🌾 For Farmers
- 🌦️ **Live Weather Dashboard** — temperature, humidity, rainfall, wind speed, pressure
- 🔍 **City Search** — look up any city worldwide
- 🌾 **Smart Crop Recommendations** — top 4 crops matched to current weather conditions
- ⚠️ **Farm Alerts** — real-time warnings like "Heavy rain — avoid irrigation"
- 📅 **Season Detection** — auto-detects Summer / Winter / Monsoon / Spring
- 🕓 **Search History** — quick-access to recently searched cities

### 🛡️ For Admins
- ➕ Add / edit / delete crops with full metadata
- ⚙️ Define weather-based crop rules (temperature, humidity, rainfall, season ranges)
- 🎛️ Set rule priority — higher priority crops appear first
- 🔁 Enable / disable crops and rules without deleting them

### 🔐 Auth System
- User registration & login
- Remember me session
- Role-based access (farmer vs admin)
- Secure logout with CSRF protection

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 11 |
| Language | PHP 8.2+ |
| Database | MySQL 8+ |
| Frontend | Blade Templates + Vanilla CSS |
| Icons | Lucide Icons (CDN) |
| Fonts | Syne + DM Sans (Google Fonts) |
| Weather API | OpenWeatherMap (free tier) |
| HTTP Client | Laravel Http (Guzzle) |

---

## 📁 Project Structure

```
cropcast/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          # Login, register, logout
│   │   │   ├── DashboardController.php     # Main dashboard logic
│   │   │   ├── WeatherController.php       # Weather fetch, JSON API, history
│   │   │   └── Admin/
│   │   │       └── CropRuleController.php  # Admin CRUD for crops & rules
│   │   └── Middleware/
│   │       └── AdminMiddleware.php         # Blocks non-admins from /admin/*
│   ├── Models/
│   │   ├── User.php                        # Auth user with isAdmin(), weatherLogs()
│   │   ├── WeatherLog.php                  # Stores fetched weather, getSeason()
│   │   ├── Crop.php                        # Crop data with emoji & waterColor accessors
│   │   └── CropRule.php                    # Rule model with matchesWeather() logic
│   └── Services/
│       ├── WeatherService.php              # OpenWeatherMap API + demo fallback + alerts
│       └── CropAdvisorService.php          # Matches weather conditions to crop rules
│
├── database/
│   ├── migrations/
│   │   ├── ..._create_users_table.php
│   │   ├── ..._create_weather_logs_table.php
│   │   ├── ..._create_crops_table.php
│   │   └── ..._create_crop_rules_table.php
│   └── seeders/
│       └── DatabaseSeeder.php              # 8 crops + 9 rules + 2 demo users
│
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php                  # Master layout, navbar, CSS design system
│   ├── auth/
│   │   ├── login.blade.php                # Split-panel login page
│   │   └── register.blade.php            # Registration form
│   ├── dashboard/
│   │   └── index.blade.php               # Main dashboard (weather + crops + alerts)
│   └── admin/
│       ├── crops/
│       │   ├── index.blade.php            # Crop list table
│       │   ├── create.blade.php           # Add crop form
│       │   └── edit.blade.php             # Edit crop form
│       └── rules/
│           ├── index.blade.php            # Rules list with condition pills
│           ├── create.blade.php           # Rule builder form
│           └── edit.blade.php             # Edit rule form
│
├── routes/
│   └── web.php                            # All 22 application routes
├── config/
│   └── services.php                       # OpenWeatherMap API key config
├── bootstrap/
│   └── app.php                            # Middleware alias registration
└── .env.example                           # Environment template
```

---

## 🗄️ Database Design

### `users`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | Auto increment |
| name | varchar | Full name |
| email | varchar unique | Login identifier |
| password | varchar | Bcrypt hashed |
| default_city | varchar | Pre-loaded on dashboard |
| is_admin | boolean | false = farmer, true = admin |
| remember_token | varchar | "Keep me signed in" |
| timestamps | — | created_at, updated_at |

### `weather_logs`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| user_id | FK → users | Cascade delete |
| city | varchar | Searched city name |
| country | varchar | Country code e.g. IN |
| temperature | decimal(5,2) | °C |
| feels_like | decimal(5,2) | °C |
| humidity | integer | % |
| rainfall | decimal(8,2) | mm last 1hr |
| wind_speed | decimal(6,2) | m/s |
| weather_main | varchar | Rain, Clear, Clouds… |
| weather_description | varchar | e.g. "heavy intensity rain" |
| weather_icon | varchar | OpenWeather icon code |
| pressure | integer | hPa |
| visibility | integer | meters |
| fetched_at | timestamp | When API was called |

### `crops`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| name | varchar | e.g. Rice |
| local_name | varchar | e.g. Chawal / Paddy |
| description | text | Growing notes |
| growing_season | varchar | Kharif / Rabi / All |
| growth_days | integer | Days to harvest |
| water_requirement | varchar | Low / Medium / High |
| is_active | boolean | Show in recommendations |

### `crop_rules`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| crop_id | FK → crops | Cascade delete |
| min_temperature | decimal | °C — nullable |
| max_temperature | decimal | °C — nullable |
| min_humidity | integer | % — nullable |
| max_humidity | integer | % — nullable |
| min_rainfall | decimal | mm — nullable |
| max_rainfall | decimal | mm — nullable |
| weather_condition | varchar | Rain, Clear… — nullable |
| season | varchar | Summer/Winter/Monsoon/Spring/All |
| priority | integer | 1–10, higher = shown first |
| advice | text | Advice message shown to farmer |
| is_active | boolean | Enable/disable rule |

### Relationships
```
User     ──hasMany──> WeatherLog
Crop     ──hasMany──> CropRule
```

---

## 🔌 API Endpoints (22 Total)

### Public
| Method | URL | Description |
|---|---|---|
| GET | `/` | Redirects to `/dashboard` |

### Guest Only
| Method | URL | Description |
|---|---|---|
| GET | `/login` | Show login form |
| POST | `/login` | Submit login credentials |
| GET | `/register` | Show registration form |
| POST | `/register` | Submit and create account |

### Auth Required
| Method | URL | Description |
|---|---|---|
| POST | `/logout` | Destroy session and logout |
| GET | `/dashboard` | Main dashboard — weather + crop recommendations |
| POST | `/dashboard/city` | Save user's preferred default city |
| GET | `/weather/fetch` | Fetch fresh weather and redirect |
| GET | `/weather/json` | Return weather + crops as JSON (AJAX) |
| GET | `/weather/history` | View all past weather searches |
| DELETE | `/weather/history/{log}` | Delete a weather log entry |

### Admin Only
| Method | URL | Description |
|---|---|---|
| GET | `/admin` | Redirect to crops index |
| GET | `/admin/crops` | List all crops |
| GET | `/admin/crops/create` | Show add crop form |
| POST | `/admin/crops` | Save new crop |
| GET | `/admin/crops/{id}/edit` | Show edit crop form |
| PUT | `/admin/crops/{id}` | Update crop record |
| DELETE | `/admin/crops/{id}` | Delete crop and its rules |
| GET | `/admin/rules` | List all crop rules |
| GET | `/admin/rules/create` | Show add rule form |
| POST | `/admin/rules` | Save new rule |
| GET | `/admin/rules/{id}/edit` | Show edit rule form |
| PUT | `/admin/rules/{id}` | Update rule |
| DELETE | `/admin/rules/{id}` | Delete rule |

---

## ⚙️ How Crop Matching Works

```
Current Weather (fetched from API)
         │
         ▼
CropAdvisorService::getRecommendations()
         │
         ▼
Load all active CropRules (with crops eager-loaded)
         │
         ▼
For each rule → CropRule::matchesWeather(weather)
  ├── temperature within [min_temperature, max_temperature]?  → fail if not
  ├── humidity within [min_humidity, max_humidity]?           → fail if not
  ├── rainfall within [min_rainfall, max_rainfall]?           → fail if not
  ├── weather_condition matches weather_main?                 → fail if not
  └── season matches weather->getSeason()?                   → fail if not
         │
         ▼
Collect matched rules, deduplicate by crop_id
Sort by priority DESC → take top 4
         │
         ▼
Return [crop, advice] pairs to dashboard view
```

### Matching Example

```
Live data:  temp=32°C  humidity=85%  rainfall=5mm  main="Rain"

Rice rule   → min_humidity:70 ✅  min_rainfall:2 ✅  condition:Rain ✅  → MATCH  #1
Sugarcane   → min_temp:27 ✅  min_humidity:60 ✅                        → MATCH  #2
Maize rule  → max_humidity:75 ❌                                        → FAIL
Mustard rule→ max_humidity:60 ❌                                        → FAIL
Wheat rule  → max_temperature:20 ❌                                     → FAIL
Millet rule → max_rainfall:0.5 ❌                                       → FAIL
```

---

## 🚀 Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- MySQL 8+
- A free [OpenWeatherMap](https://openweathermap.org/api) API key *(optional)*

### Step 1 — Create Laravel Project
```bash
composer create-project laravel/laravel cropcast
cd cropcast
```

### Step 2 — Copy Project Files
Copy all files from this package into the matching paths of your Laravel install.

### Step 3 — Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
DB_DATABASE=weather_crop_db
DB_USERNAME=root
DB_PASSWORD=your_password

OPENWEATHER_API_KEY=your_key_here
```

### Step 4 — Create the Database
```sql
CREATE DATABASE weather_crop_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 5 — Run Migrations & Seed
```bash
php artisan migrate
php artisan db:seed
```

This seeds: **8 crops**, **9 rules**, **1 admin user**, **1 demo farmer**.

### Step 6 — Start the Server
```bash
php artisan serve
```

Open → **http://localhost:8000**

---

## 🔑 Demo Credentials

| Role | Email | Password |
|---|---|---|
| 🛡️ Admin | admin@cropweather.com | password |
| 👨‍🌾 Farmer | demo@cropweather.com | password |

---

## 🌍 Demo Mode (No API Key Needed)

If `OPENWEATHER_API_KEY` is left blank, the app uses **built-in rotating demo data** based on the city name. Every feature — dashboard, crop matching, alerts, history — works fully without an API key.

To switch to live data:
1. Register free at [openweathermap.org](https://openweathermap.org/api)
2. Copy your API key from the **API keys** section
3. Add to `.env`: `OPENWEATHER_API_KEY=your_key_here`
4. Run `php artisan config:clear`

> ⚠️ New API keys take ~10 minutes to activate after registration.

---

## 🧪 Troubleshooting

| Problem | Solution |
|---|---|
| `Class not found` errors | `composer dump-autoload` |
| 403 on `/admin/*` routes | Check `bootstrap/app.php` has the `admin` middleware alias |
| Weather still shows demo data | Add key to `.env` then run `php artisan config:clear` |
| Database migration errors | `php artisan migrate:fresh --seed` |
| Blank page / 500 error | Check `storage/logs/laravel.log` |
| Seeder fails on re-run | `php artisan migrate:fresh --seed` to reset cleanly |

---

## 🌱 Seeded Crops & Rules

| Crop | Local Name | Water | Season | Trigger Condition |
|---|---|---|---|---|
| Rice | Chawal / Paddy | High | Kharif | Humidity ≥ 70%, Rain |
| Wheat | Gehun | Medium | Rabi | Temp ≤ 20°C, dry |
| Millet | Bajra / Jowar | Low | Kharif | Rainfall ≤ 0.5mm, hot |
| Maize | Makka / Corn | Medium | All | Temp 18–32°C, humidity 40–75% |
| Cotton | Kapas | Medium | Kharif | Hot, Clear sky |
| Mustard | Sarson | Low | Rabi | Temp ≤ 18°C, dry |
| Sugarcane | Ganna | High | All | Temp ≥ 27°C, humid |
| Tomato | Tamatar | Medium | All | Temp 15–28°C, humidity 50–75% |

---

## 🔮 Possible Future Enhancements

- [ ] 7-day weather forecast view
- [ ] Email alerts for dangerous weather conditions
- [ ] Crop calendar with planting and harvest date tracking
- [ ] GPS-based auto location detection
- [ ] Export crop recommendations as PDF
- [ ] Temperature history chart
- [ ] Multi-language support (Hindi, Punjabi, Tamil…)
- [ ] REST API for mobile app integration

---

## 📄 License

This project is open-source and available under the [MIT License](https://opensource.org/licenses/MIT).

---

## 👨‍💻 Built With

- [Laravel](https://laravel.com) — The PHP Framework for Web Artisans
- [OpenWeatherMap API](https://openweathermap.org/api) — Free weather data
- [Lucide Icons](https://lucide.dev) — Beautiful open-source icons
- [Google Fonts](https://fonts.google.com) — Syne + DM Sans typography