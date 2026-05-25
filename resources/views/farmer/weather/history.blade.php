{{-- File: resources/views/farmer/weather/history.blade.php --}}
@extends('layouts.app')

@section('title', 'Weather History')

@push('styles')
<style>
.history-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
}

.chart-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
}

.chart-card.full-width {
    grid-column: 1 / -1;
}

.chart-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.25rem;
}

.chart-title {
    font-family: var(--font-display);
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 8px;
}

.chart-title i { width: 16px; height: 16px; color: var(--green-bright); }

.chart-subtitle {
    font-size: 0.78rem;
    color: var(--text-muted);
}

canvas { width: 100% !important; }

/* ─── Filter Bar ───── */
.filter-bar {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1rem 1.5rem;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.filter-label {
    font-size: 0.82rem;
    color: var(--text-muted);
    font-weight: 500;
}

.filter-btn {
    padding: 5px 14px;
    border-radius: 20px;
    border: 1px solid var(--border);
    background: transparent;
    color: var(--text-secondary);
    font-size: 0.82rem;
    cursor: pointer;
    transition: var(--transition);
}

.filter-btn.active, .filter-btn:hover {
    background: rgba(82,183,136,0.12);
    border-color: var(--green-mid);
    color: var(--green-bright);
}

/* ─── Table ───── */
.history-table-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    margin-top: 1.25rem;
}

.history-table-card table {
    width: 100%;
    border-collapse: collapse;
}

.history-table-card th {
    padding: 0.85rem 1.25rem;
    text-align: left;
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.06em;
    border-bottom: 1px solid var(--border);
    background: var(--bg-card-2);
}

.history-table-card td {
    padding: 0.85rem 1.25rem;
    font-size: 0.875rem;
    color: var(--text-secondary);
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
}

.history-table-card tr:last-child td { border-bottom: none; }

.history-table-card tr:hover td { background: var(--bg-hover); color: var(--text-primary); }

.temp-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-weight: 600;
    color: var(--amber);
}

.weather-icon-sm { width: 32px; height: 32px; }

.stat-chip {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.78rem;
    background: rgba(82,183,136,0.08);
    color: var(--green-bright);
    border: 1px solid rgba(82,183,136,0.15);
}

.delete-btn {
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: 4px;
    border-radius: 6px;
    transition: var(--transition);
    display: inline-flex;
}

.delete-btn:hover { color: var(--red-alert); background: rgba(230,57,70,0.1); }
.delete-btn i { width: 14px; height: 14px; }

.pagination-wrap {
    display: flex;
    justify-content: center;
    padding: 1rem;
    gap: 6px;
}

.page-btn {
    padding: 5px 12px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
    background: transparent;
    color: var(--text-secondary);
    font-size: 0.82rem;
    text-decoration: none;
    transition: var(--transition);
}

.page-btn:hover, .page-btn.active {
    background: rgba(82,183,136,0.12);
    border-color: var(--green-mid);
    color: var(--green-bright);
}

/* ─── Summary Stats ───── */
.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.25rem;
}

.stat-mini {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 1rem 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.stat-mini-label { font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }
.stat-mini-value { font-family: var(--font-display); font-size: 1.4rem; font-weight: 700; color: var(--text-primary); }
.stat-mini-sub { font-size: 0.75rem; color: var(--text-secondary); }

@media (max-width: 768px) {
    .history-grid { grid-template-columns: 1fr; }
    .stats-row { grid-template-columns: 1fr 1fr; }
    .chart-card.full-width { grid-column: 1; }
}
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-family:var(--font-display);font-size:1.6rem;font-weight:800;color:var(--text-primary);">
            <i data-lucide="chart-line" style="width:22px;height:22px;vertical-align:middle;margin-right:8px;color:var(--green-bright);"></i>
            Weather History
        </h1>
        <p style="color:var(--text-muted);font-size:0.875rem;margin-top:4px;">
            Your logged weather data with temperature, humidity &amp; rainfall trends
        </p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-ghost" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:var(--radius-sm);border:1px solid var(--border);color:var(--text-secondary);text-decoration:none;font-size:0.875rem;transition:var(--transition);">
        <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Back to Dashboard
    </a>
</div>

@if(session('success'))
    <div style="background:rgba(82,183,136,0.1);border:1px solid var(--green-mid);color:var(--green-bright);border-radius:var(--radius-md);padding:0.85rem 1.25rem;margin-bottom:1.25rem;font-size:0.875rem;display:flex;align-items:center;gap:8px;">
        <i data-lucide="check-circle" style="width:16px;height:16px;flex-shrink:0;"></i>
        {{ session('success') }}
    </div>
@endif

{{-- Summary Stats --}}
@php
    $allLogs = Auth::user()->weatherLogs()->get();
    $avgTemp   = $allLogs->avg('temperature');
    $avgHumid  = $allLogs->avg('humidity');
    $totalRain = $allLogs->sum('rainfall');
    $totalLogs = $allLogs->count();
@endphp

<div class="stats-row">
    <div class="stat-mini">
        <span class="stat-mini-label">Total Logs</span>
        <span class="stat-mini-value">{{ $totalLogs }}</span>
        <span class="stat-mini-sub">weather records</span>
    </div>
    <div class="stat-mini">
        <span class="stat-mini-label">Avg Temperature</span>
        <span class="stat-mini-value" style="color:var(--amber);">{{ $avgTemp ? round($avgTemp, 1) : '—' }}°C</span>
        <span class="stat-mini-sub">across all logs</span>
    </div>
    <div class="stat-mini">
        <span class="stat-mini-label">Avg Humidity</span>
        <span class="stat-mini-value" style="color:var(--sky);">{{ $avgHumid ? round($avgHumid) : '—' }}%</span>
        <span class="stat-mini-sub">across all logs</span>
    </div>
    <div class="stat-mini">
        <span class="stat-mini-label">Total Rainfall</span>
        <span class="stat-mini-value" style="color:var(--green-bright);">{{ round($totalRain, 1) }} mm</span>
        <span class="stat-mini-sub">cumulative</span>
    </div>
</div>

{{-- Filter Bar --}}
<div class="filter-bar">
    <span class="filter-label">Show last:</span>
    <button class="filter-btn active" onclick="filterChart(7, this)">7 days</button>
    <button class="filter-btn" onclick="filterChart(14, this)">14 days</button>
    <button class="filter-btn" onclick="filterChart(30, this)">30 days</button>
    <button class="filter-btn" onclick="filterChart(null, this)">All time</button>
</div>

{{-- Charts Grid --}}
<div class="history-grid">
    <div class="chart-card full-width">
        <div class="chart-header">
            <div class="chart-title">
                <i data-lucide="thermometer"></i> Temperature Over Time
            </div>
            <span class="chart-subtitle">°C — daily readings</span>
        </div>
        <canvas id="tempChart" height="90"></canvas>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title">
                <i data-lucide="droplets"></i> Humidity %
            </div>
            <span class="chart-subtitle">percentage</span>
        </div>
        <canvas id="humidChart" height="140"></canvas>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title">
                <i data-lucide="cloud-rain"></i> Rainfall (mm)
            </div>
            <span class="chart-subtitle">mm per reading</span>
        </div>
        <canvas id="rainChart" height="140"></canvas>
    </div>
</div>

{{-- History Table --}}
<div class="history-table-card">
    <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px;">
        <i data-lucide="table" style="width:16px;height:16px;color:var(--green-bright);"></i>
        <span style="font-family:var(--font-display);font-size:0.95rem;font-weight:700;color:var(--text-primary);">Log Entries</span>
        <span style="margin-left:auto;font-size:0.78rem;color:var(--text-muted);">{{ $logs->total() }} total records</span>
    </div>

    @if($logs->isEmpty())
        <div style="padding:3rem;text-align:center;color:var(--text-muted);">
            <i data-lucide="cloud-off" style="width:40px;height:40px;margin:0 auto 1rem;display:block;opacity:0.4;"></i>
            <p>No weather history yet. Search for a city on the dashboard to start logging.</p>
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Condition</th>
                    <th>City</th>
                    <th>Temp</th>
                    <th>Humidity</th>
                    <th>Rainfall</th>
                    <th>Wind</th>
                    <th>Date &amp; Time</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            @if($log->weather_icon)
                                <img src="{{ $log->icon_url }}" class="weather-icon-sm" alt="{{ $log->weather_description }}">
                            @endif
                            <span style="color:var(--text-primary);font-weight:500;text-transform:capitalize;">
                                {{ $log->weather_description ?? $log->weather_main }}
                            </span>
                        </div>
                    </td>
                    <td>
                        {{ $log->city }}@if($log->country), <span style="color:var(--text-muted);">{{ $log->country }}</span>@endif
                    </td>
                    <td><span class="temp-badge"><i data-lucide="thermometer" style="width:13px;height:13px;"></i>{{ round($log->temperature) }}°C</span></td>
                    <td><span class="stat-chip" style="color:var(--sky);border-color:rgba(72,202,228,0.2);background:rgba(72,202,228,0.06);">💧 {{ $log->humidity }}%</span></td>
                    <td>
                        @if($log->rainfall > 0)
                            <span class="stat-chip">🌧 {{ $log->rainfall }} mm</span>
                        @else
                            <span style="color:var(--text-muted);">—</span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);">
                        {{ $log->wind_speed ? round($log->wind_speed, 1) . ' m/s' : '—' }}
                    </td>
                    <td style="color:var(--text-muted);font-size:0.82rem;">
                        {{ $log->fetched_at->format('d M Y, g:i A') }}
                    </td>
                    <td>
                        <form method="POST" action="{{ route('weather.log.destroy', $log) }}" onsubmit="return confirm('Delete this log entry?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="delete-btn" title="Delete">
                                <i data-lucide="trash-2"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($logs->hasPages())
        <div class="pagination-wrap">
            @if($logs->onFirstPage())
                <span class="page-btn" style="opacity:0.4;">‹ Prev</span>
            @else
                <a href="{{ $logs->previousPageUrl() }}" class="page-btn">‹ Prev</a>
            @endif

            @foreach($logs->getUrlRange(max(1, $logs->currentPage()-2), min($logs->lastPage(), $logs->currentPage()+2)) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $page == $logs->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach

            @if($logs->hasMorePages())
                <a href="{{ $logs->nextPageUrl() }}" class="page-btn">Next ›</a>
            @else
                <span class="page-btn" style="opacity:0.4;">Next ›</span>
            @endif
        </div>
        @endif
    @endif
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── Raw data from server (chronological order) ──
@php
    $chartData = Auth::user()->weatherLogs()
        ->orderBy('fetched_at')
        ->get(['fetched_at', 'temperature', 'humidity', 'rainfall', 'city'])
        ->map(fn($l) => [
            'date'        => $l->fetched_at->format('d M, g A'),
            'temperature' => round($l->temperature, 1),
            'humidity'    => $l->humidity,
            'rainfall'    => round($l->rainfall, 2),
        ]);
@endphp
const rawData = @json($chartData);

// Chart defaults
Chart.defaults.color = '#8aad96';
Chart.defaults.borderColor = '#243328';
Chart.defaults.font.family = "'DM Sans', sans-serif";

const commonOptions = {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: {
        x: { grid: { color: '#1a2a1f' }, ticks: { maxTicksLimit: 8, font: { size: 11 } } },
        y: { grid: { color: '#1a2a1f' }, ticks: { font: { size: 11 } } }
    },
    elements: { point: { radius: 3, hoverRadius: 5 } }
};

let visibleData = rawData;
let tempChart, humidChart, rainChart;

function buildCharts(data) {
    const labels = data.map(d => d.date);

    const destroy = (c) => { if (c) c.destroy(); };
    destroy(tempChart); destroy(humidChart); destroy(rainChart);

    tempChart = new Chart(document.getElementById('tempChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                data: data.map(d => d.temperature),
                borderColor: '#f4a261',
                backgroundColor: 'rgba(244,162,97,0.08)',
                fill: true,
                tension: 0.4,
            }]
        },
        options: { ...commonOptions }
    });

    humidChart = new Chart(document.getElementById('humidChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                data: data.map(d => d.humidity),
                borderColor: '#48cae4',
                backgroundColor: 'rgba(72,202,228,0.08)',
                fill: true,
                tension: 0.4,
            }]
        },
        options: { ...commonOptions, scales: { ...commonOptions.scales, y: { ...commonOptions.scales.y, min: 0, max: 100 } } }
    });

    rainChart = new Chart(document.getElementById('rainChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                data: data.map(d => d.rainfall),
                backgroundColor: 'rgba(82,183,136,0.5)',
                borderColor: '#52b788',
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: { ...commonOptions }
    });
}

function filterChart(days, btn) {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    if (days === null) {
        visibleData = rawData;
    } else {
        const cutoff = new Date();
        cutoff.setDate(cutoff.getDate() - days);
        visibleData = rawData.slice(-days * 4); // approx — just tail the array
    }
    buildCharts(visibleData);
}

buildCharts(rawData);
</script>
@endpush