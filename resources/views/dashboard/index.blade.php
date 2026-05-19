
@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
/* ─── Dashboard Grid ─────────────────────────────────── */
.dash-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    grid-template-rows: auto;
    gap: 1.25rem;
}

/* ─── Search Bar ─────────────────────────────────────── */
.search-section {
    grid-column: 1 / -1;
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: space-between;
}

.search-form {
    display: flex;
    gap: 10px;
    flex: 1;
    max-width: 480px;
}

.search-input {
    flex: 1;
    padding: 10px 16px 10px 42px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    color: var(--text-primary);
    font-family: var(--font-body);
    font-size: 0.9rem;
    outline: none;
    transition: var(--transition);
    position: relative;
}

.search-wrap { position: relative; flex: 1; }

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    width: 16px; height: 16px;
    pointer-events: none;
}

.search-input:focus {
    border-color: var(--green-mid);
    box-shadow: 0 0 0 3px rgba(64,145,108,0.15);
}

.search-input::placeholder { color: var(--text-muted); }

.page-title {
    font-family: var(--font-display);
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-secondary);
}

.page-title strong { color: var(--text-primary); }

/* ─── Hero Weather Card ──────────────────────────────── */
.weather-hero {
    grid-column: 1 / 2;
    grid-row: 2 / 4;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    padding: 1.75rem;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-card);
}

.weather-hero::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(82,183,136,0.08) 0%, transparent 70%);
    pointer-events: none;
}

.weather-city {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--text-muted);
    font-size: 0.82rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 1.25rem;
}

.weather-city i { width: 13px; height: 13px; color: var(--green-bright); }

.weather-main-temp {
    font-family: var(--font-display);
    font-size: 5rem;
    font-weight: 800;
    line-height: 1;
    letter-spacing: -0.04em;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.weather-main-temp .unit {
    font-size: 2rem;
    color: var(--text-muted);
    font-weight: 400;
}

.weather-icon-desc {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 1.75rem;
}

.weather-icon-img {
    width: 52px; height: 52px;
    filter: drop-shadow(0 0 8px rgba(82,183,136,0.3));
}

.weather-desc {
    font-size: 1.1rem;
    font-weight: 500;
    color: var(--text-secondary);
    text-transform: capitalize;
}

.weather-feels {
    font-size: 0.82rem;
    color: var(--text-muted);
}

.weather-divider {
    height: 1px;
    background: var(--border);
    margin: 1.25rem 0;
}

.weather-stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
}

.weather-stat {
    background: var(--bg-base);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 12px 14px;
    position: relative;
    overflow: hidden;
}

.weather-stat::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    height: 2px;
    width: 100%;
}

.stat-humidity::after { background: linear-gradient(90deg, var(--sky), transparent); }
.stat-rain::after     { background: linear-gradient(90deg, #6ca6d4, transparent); }
.stat-wind::after     { background: linear-gradient(90deg, var(--green-bright), transparent); }
.stat-pressure::after { background: linear-gradient(90deg, var(--amber), transparent); }

.stat-label {
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 4px;
}

.stat-label i { width: 11px; height: 11px; }

.stat-value {
    font-family: var(--font-display);
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--text-primary);
    line-height: 1.2;
}

.stat-value .unit {
    font-size: 0.75rem;
    font-weight: 400;
    color: var(--text-muted);
}

.weather-updated {
    margin-top: 1.25rem;
    font-size: 0.75rem;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 5px;
}

.live-dot {
    width: 6px; height: 6px;
    background: var(--green-bright);
    border-radius: 50%;
    animation: pulse 2s ease infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: 0.5; transform: scale(1.3); }
}

/* ─── Season Badge ───────────────────────────────────── */
.season-card {
    grid-column: 2 / 3;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.season-icon {
    font-size: 3rem;
    line-height: 1;
    margin-bottom: 0.75rem;
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
}

.season-name {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 800;
    letter-spacing: -0.02em;
    color: var(--text-primary);
}

.season-sub {
    font-size: 0.82rem;
    color: var(--text-muted);
    margin-top: 2px;
}

.summary-text {
    font-size: 0.85rem;
    color: var(--text-secondary);
    line-height: 1.6;
    background: var(--bg-base);
    border-radius: var(--radius-md);
    padding: 10px 12px;
    border: 1px solid var(--border);
    margin-top: 0.75rem;
}

/* ─── Alerts Card ────────────────────────────────────── */
.alerts-card {
    grid-column: 3 / 4;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    overflow: hidden;
}

.alerts-list { display: flex; flex-direction: column; gap: 8px; }

.alert-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 10px 12px;
    border-radius: var(--radius-md);
    font-size: 0.82rem;
    line-height: 1.5;
    animation: fadeIn 0.4s ease;
}

@keyframes fadeIn { from { opacity: 0; transform: translateX(8px); } to { opacity: 1; transform: none; } }

.alert-item.danger  { background: rgba(230,57,70,0.08);  border: 1px solid rgba(230,57,70,0.2);  color: #ff8a93; }
.alert-item.warning { background: rgba(244,162,97,0.08); border: 1px solid rgba(244,162,97,0.2); color: #f4c87d; }
.alert-item.success { background: rgba(82,183,136,0.08); border: 1px solid rgba(82,183,136,0.2); color: var(--green-pale); }

.alert-icon { font-size: 1rem; flex-shrink: 0; line-height: 1.5; }

/* ─── Crop Recommendations ───────────────────────────── */
.crops-section {
    grid-column: 1 / -1;
}

.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.section-title {
    font-family: var(--font-display);
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-title i { width: 16px; height: 16px; color: var(--green-bright); }

.crops-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}

.crop-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
    transition: var(--transition);
    cursor: default;
}

.crop-card:hover {
    border-color: var(--green-mid);
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.4), 0 0 20px rgba(82,183,136,0.08);
}

.crop-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--green-mid), var(--green-bright));
    opacity: 0;
    transition: var(--transition);
}

.crop-card:hover::before { opacity: 1; }

.crop-rank {
    position: absolute;
    top: 12px; right: 12px;
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--text-muted);
    background: var(--bg-base);
    border: 1px solid var(--border);
    border-radius: 100px;
    padding: 2px 8px;
}

.crop-emoji {
    font-size: 2.5rem;
    line-height: 1;
    margin-bottom: 0.75rem;
    display: block;
    filter: drop-shadow(0 3px 6px rgba(0,0,0,0.3));
}

.crop-name {
    font-family: var(--font-display);
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--text-primary);
    letter-spacing: -0.02em;
    margin-bottom: 1px;
}

.crop-local {
    font-size: 0.78rem;
    color: var(--text-muted);
    margin-bottom: 0.75rem;
}

.crop-advice {
    font-size: 0.8rem;
    color: var(--text-secondary);
    line-height: 1.55;
    margin-bottom: 1rem;
    border-top: 1px solid var(--border);
    padding-top: 0.75rem;
}

.crop-meta {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.crop-tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 8px;
    border-radius: 100px;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.03em;
}

.tag-season  { background: rgba(82,183,136,0.1);  color: var(--green-bright); border: 1px solid rgba(82,183,136,0.2); }
.tag-water-low    { background: rgba(244,162,97,0.1); color: var(--amber);        border: 1px solid rgba(244,162,97,0.2); }
.tag-water-medium { background: rgba(72,202,228,0.1); color: var(--sky);          border: 1px solid rgba(72,202,228,0.2); }
.tag-water-high   { background: rgba(82,183,136,0.1); color: var(--green-bright); border: 1px solid rgba(82,183,136,0.2); }
.tag-days    { background: rgba(77,107,87,0.2);   color: var(--text-muted);   border: 1px solid var(--border); }

/* ─── No Recommendations ─────────────────────────────── */
.no-recommendations {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem;
    background: var(--bg-card);
    border: 1px dashed var(--border-light);
    border-radius: var(--radius-xl);
    color: var(--text-muted);
}

.no-recommendations .icon { font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }
.no-recommendations h3 { font-family: var(--font-display); font-size: 1.1rem; margin-bottom: 6px; color: var(--text-secondary); }

/* ─── History Card ───────────────────────────────────── */
.history-card {
    grid-column: 1 / -1;
}

.history-items {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 0.75rem;
}

.history-item {
    background: var(--bg-base);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 12px;
    transition: var(--transition);
}

.history-item:hover {
    border-color: var(--border-light);
    background: var(--bg-card);
}

.history-city {
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--text-primary);
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.history-temp {
    font-family: var(--font-display);
    font-size: 1.3rem;
    font-weight: 800;
    color: var(--green-bright);
    line-height: 1.1;
}

.history-when {
    font-size: 0.72rem;
    color: var(--text-muted);
    margin-top: 4px;
}

/* ─── Error State ────────────────────────────────────── */
.error-state {
    grid-column: 1 / -1;
    background: rgba(230,57,70,0.06);
    border: 1px solid rgba(230,57,70,0.2);
    border-radius: var(--radius-xl);
    padding: 3rem;
    text-align: center;
}

.error-state .icon { font-size: 3rem; margin-bottom: 1rem; }
.error-state h2 { font-family: var(--font-display); font-size: 1.2rem; color: #ff6b7a; margin-bottom: 6px; }
.error-state p  { color: var(--text-muted); font-size: 0.875rem; }

/* ─── Responsive ─────────────────────────────────────── */
@media (max-width: 1100px) {
    .dash-grid { grid-template-columns: 1fr 1fr; }
    .weather-hero { grid-column: 1 / 2; grid-row: auto; }
    .crops-grid { grid-template-columns: repeat(2, 1fr); }
    .history-items { grid-template-columns: repeat(3, 1fr); }
}

@media (max-width: 640px) {
    .dash-grid { grid-template-columns: 1fr; }
    .weather-hero { grid-column: 1; grid-row: auto; }
    .crops-grid { grid-template-columns: 1fr 1fr; }
    .history-items { grid-template-columns: repeat(2, 1fr); }
    .alerts-card { grid-column: 1; }
}
</style>
@endpush

@section('content')
<div class="page-wrapper">
<div class="dash-grid">

    {{-- ─── Search Bar ──────────────────────────────────────────── --}}
    <div class="search-section">
        <div class="page-title">
            Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }},
            <strong>{{ auth()->user()->name }}</strong> 👋
        </div>

        <form action="{{ route('dashboard') }}" method="GET" class="search-form">
            <div class="search-wrap">
                <i data-lucide="map-pin" class="search-icon"></i>
                <input
                    type="text"
                    name="city"
                    class="search-input"
                    placeholder="Search city… e.g. Mumbai, Delhi, London"
                    value="{{ $city }}"
                    autocomplete="off"
                >
            </div>
            <button type="submit" class="btn btn-primary">
                <i data-lucide="search"></i> Fetch
            </button>
        </form>
    </div>

    {{-- ─── Error State ─────────────────────────────────────────── --}}
    @if(isset($error))
    <div class="error-state">
        <div class="icon">🌐</div>
        <h2>Weather fetch failed</h2>
        <p>{{ $error }}</p>
    </div>
    @endif

    {{-- ─── Main Weather Hero ───────────────────────────────────── --}}
    @if($weather)
    <div class="weather-hero">
        <div class="weather-city">
            <i data-lucide="map-pin"></i>
            {{ $weather->city }}@if($weather->country), {{ $weather->country }}@endif
        </div>

        <div class="weather-main-temp">
            {{ round($weather->temperature) }}<span class="unit">°C</span>
        </div>

        <div class="weather-icon-desc">
            @if($weather->weather_icon)
            <img
                src="https://openweathermap.org/img/wn/{{ $weather->weather_icon }}@2x.png"
                alt="{{ $weather->weather_description }}"
                class="weather-icon-img"
                onerror="this.style.display='none'"
            >
            @endif
            <div>
                <div class="weather-desc">{{ ucfirst($weather->weather_description ?? $weather->weather_main) }}</div>
                @if($weather->feels_like)
                <div class="weather-feels">Feels like {{ round($weather->feels_like) }}°C</div>
                @endif
            </div>
        </div>

        <div class="weather-divider"></div>

        <div class="weather-stats-grid">
            <div class="weather-stat stat-humidity">
                <div class="stat-label"><i data-lucide="droplets"></i> Humidity</div>
                <div class="stat-value">{{ $weather->humidity }}<span class="unit">%</span></div>
            </div>
            <div class="weather-stat stat-rain">
                <div class="stat-label"><i data-lucide="cloud-rain"></i> Rainfall</div>
                <div class="stat-value">{{ $weather->rainfall > 0 ? number_format($weather->rainfall, 1) : '0' }}<span class="unit"> mm</span></div>
            </div>
            <div class="weather-stat stat-wind">
                <div class="stat-label"><i data-lucide="wind"></i> Wind</div>
                <div class="stat-value">{{ $weather->wind_speed ? round($weather->wind_speed) : '—' }}<span class="unit"> m/s</span></div>
            </div>
            <div class="weather-stat stat-pressure">
                <div class="stat-label"><i data-lucide="gauge"></i> Pressure</div>
                <div class="stat-value">{{ $weather->pressure ?? '—' }}<span class="unit"> hPa</span></div>
            </div>
        </div>

        <div class="weather-updated">
            <div class="live-dot"></div>
            Updated {{ $weather->fetched_at->diffForHumans() }}
        </div>
    </div>

    {{-- ─── Season Card ─────────────────────────────────────────── --}}
    <div class="season-card">
        <div>
            @php
                $season = $weather->getSeason();
                $seasonData = [
                    'Summer'  => ['🌞', 'Summer Season',  'High heat, water crops frequently'],
                    'Winter'  => ['❄️',  'Winter Season',  'Cool dry air, ideal for Rabi crops'],
                    'Monsoon' => ['🌧️', 'Monsoon Season', 'Heavy rainfall, ideal for Kharif'],
                    'Spring'  => ['🌸', 'Spring Season',  'Mild weather, good for most crops'],
                ][$season] ?? ['🌿', $season . ' Season', 'Check crop rules for guidance'];
            @endphp
            <div class="season-icon">{{ $seasonData[0] }}</div>
            <div class="season-name">{{ $seasonData[1] }}</div>
            <div class="season-sub">{{ $seasonData[2] }}</div>
        </div>

        @if($summary)
        <div class="summary-text">
            📊 {{ $summary }}
        </div>
        @endif

        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:0.75rem;">
            <span class="badge badge-green">{{ $weather->weather_main }}</span>
            <span class="badge badge-muted">{{ now()->format('d M Y') }}</span>
            @if($weather->rainfall > 5)
            <span class="badge badge-sky">🌧️ Rain Alert</span>
            @endif
        </div>
    </div>

    {{-- ─── Alerts Card ─────────────────────────────────────────── --}}
    <div class="alerts-card">
        <div class="card-title">
            <i data-lucide="triangle-alert"></i>
            Farm Alerts
        </div>
        <div class="alerts-list">
            @foreach($alerts as $alert)
            <div class="alert-item {{ $alert['type'] }}">
                <span class="alert-icon">{{ $alert['icon'] }}</span>
                <span>{{ $alert['message'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ─── Crop Recommendations ────────────────────────────────── --}}
    @if($weather)
    <div class="crops-section">
        <div class="section-header">
            <div class="section-title">
                <i data-lucide="sprout"></i>
                Recommended Crops
                <span class="badge badge-green" style="font-size:0.7rem;">{{ $recommendations->count() }} matches</span>
            </div>
            <span style="font-size:0.8rem;color:var(--text-muted);">Based on current weather conditions</span>
        </div>

        @if($recommendations->count() > 0)
        <div class="crops-grid">
            @foreach($recommendations as $index => $item)
            @php $crop = $item['crop']; @endphp
            <div class="crop-card">
                <div class="crop-rank">#{{ $index + 1 }}</div>
                <span class="crop-emoji">{{ $crop->emoji }}</span>
                <div class="crop-name">{{ $crop->name }}</div>
                <div class="crop-local">{{ $crop->local_name }}</div>
                <div class="crop-advice">{{ $item['advice'] }}</div>
                <div class="crop-meta">
                    @if($crop->growing_season)
                    <span class="crop-tag tag-season">{{ $crop->growing_season }}</span>
                    @endif
                    @if($crop->water_requirement)
                    <span class="crop-tag tag-water-{{ strtolower($crop->water_requirement) }}">
                        💧 {{ $crop->water_requirement }}
                    </span>
                    @endif
                    @if($crop->growth_days)
                    <span class="crop-tag tag-days">~{{ $crop->growth_days }}d</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="no-recommendations">
            <div class="icon">🌱</div>
            <h3>No exact matches found</h3>
            <p>Current conditions don't closely match any defined crop rules.<br>
            Ask your admin to add more rules, or try a different city.</p>
        </div>
        @endif
    </div>

    {{-- ─── Recent History ──────────────────────────────────────── --}}
    @if($history->count() > 0)
    <div class="history-card">
        <div class="section-header" style="margin-bottom:0.75rem;">
            <div class="section-title">
                <i data-lucide="history"></i>
                Recent Searches
            </div>
        </div>
        <div class="history-items">
            @foreach($history as $log)
            <a href="{{ route('dashboard', ['city' => $log->city]) }}" style="text-decoration:none;">
                <div class="history-item">
                    <div class="history-city">{{ $log->city }}</div>
                    <div class="history-temp">{{ round($log->temperature) }}°C</div>
                    <div class="history-when">{{ $log->fetched_at->diffForHumans() }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
    @endif

</div>
</div>
@endsection