{{-- File: resources/views/farmer/irrigation/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Irrigation Tracker')

@push('styles')
<style>
.irr-layout {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 1.25rem;
    align-items: start;
}

/* Form card (reuse planner styles) */
.form-card { background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;position:sticky;top:80px; }
.form-card-header { padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);background:var(--bg-card-2);display:flex;align-items:center;gap:8px; }
.form-card-header h2 { font-family:var(--font-display);font-size:1rem;font-weight:700;color:var(--text-primary); }
.form-card-header i { width:16px;height:16px;color:var(--sky); }
.form-body { padding:1.5rem;display:flex;flex-direction:column;gap:1rem; }
.form-group { display:flex;flex-direction:column;gap:6px; }
.form-label { font-size:0.8rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em; }
.form-input,.form-select,.form-textarea { padding:10px 14px;background:var(--bg-base);border:1px solid var(--border);border-radius:var(--radius-sm);color:var(--text-primary);font-family:var(--font-body);font-size:0.875rem;outline:none;transition:var(--transition);width:100%; }
.form-input:focus,.form-select:focus { border-color:var(--sky);box-shadow:0 0 0 3px rgba(72,202,228,0.1); }
.form-select option { background:var(--bg-card); }
.form-row { display:grid;grid-template-columns:1fr 1fr;gap:10px; }
.form-textarea { resize:vertical;min-height:70px; }
.error-msg { font-size:0.78rem;color:var(--red-alert); }
.btn-primary-irr { padding:11px;background:linear-gradient(135deg,#0077b6,var(--sky));border:none;border-radius:var(--radius-sm);color:#fff;font-family:var(--font-display);font-size:0.9rem;font-weight:700;cursor:pointer;transition:var(--transition);width:100%; }
.btn-primary-irr:hover { opacity:0.9;transform:translateY(-1px); }

/* ─── Stats ───── */
.irr-stats { display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.25rem; }
.irr-stat { background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-md);padding:1rem 1.25rem; }
.irr-stat-label { font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em; }
.irr-stat-value { font-family:var(--font-display);font-size:1.4rem;font-weight:700;color:var(--sky);margin-top:4px; }
.irr-stat-sub { font-size:0.75rem;color:var(--text-secondary);margin-top:2px; }

/* ─── Upcoming Banner ───── */
.upcoming-banner { background:rgba(72,202,228,0.07);border:1px solid rgba(72,202,228,0.2);border-radius:var(--radius-md);padding:1rem 1.25rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:10px;font-size:0.875rem;color:var(--sky); }
.upcoming-banner i { width:18px;height:18px;flex-shrink:0; }
.upcoming-banner strong { color:var(--text-primary); }

/* ─── Log Table ───── */
.irr-table-card { background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden; }
.irr-table-card table { width:100%;border-collapse:collapse; }
.irr-table-card th { padding:0.85rem 1.25rem;text-align:left;font-size:0.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;border-bottom:1px solid var(--border);background:var(--bg-card-2); }
.irr-table-card td { padding:0.85rem 1.25rem;font-size:0.875rem;color:var(--text-secondary);border-bottom:1px solid var(--border);vertical-align:middle; }
.irr-table-card tr:last-child td { border-bottom:none; }
.irr-table-card tr:hover td { background:var(--bg-hover);color:var(--text-primary); }

.method-chip { display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:12px;font-size:0.78rem;background:rgba(72,202,228,0.08);color:var(--sky);border:1px solid rgba(72,202,228,0.15); }

.next-ok      { color:var(--green-bright); }
.next-soon    { color:var(--gold); }
.next-today   { color:var(--amber); font-weight:600; }
.next-overdue { color:var(--red-alert); font-weight:600; }

.del-btn { background:none;border:none;color:var(--text-muted);cursor:pointer;padding:4px;border-radius:6px;transition:var(--transition);display:inline-flex; }
.del-btn:hover { color:var(--red-alert);background:rgba(230,57,70,0.1); }
.del-btn i { width:14px;height:14px; }

.pagination-wrap { display:flex;justify-content:center;padding:1rem;gap:6px; }
.page-btn { padding:5px 12px;border-radius:var(--radius-sm);border:1px solid var(--border);background:transparent;color:var(--text-secondary);font-size:0.82rem;text-decoration:none;transition:var(--transition); }
.page-btn:hover,.page-btn.active { background:rgba(72,202,228,0.1);border-color:var(--sky);color:var(--sky); }

@media (max-width:900px) {
    .irr-layout { grid-template-columns:1fr; }
    .form-card { position:static; }
    .irr-stats { grid-template-columns:1fr 1fr; }
}
</style>
@endpush

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-family:var(--font-display);font-size:1.6rem;font-weight:800;color:var(--text-primary);">
            <i data-lucide="droplets" style="width:22px;height:22px;vertical-align:middle;margin-right:8px;color:var(--sky);"></i>
            Irrigation Tracker
        </h1>
        <p style="color:var(--text-muted);font-size:0.875rem;margin-top:4px;">
            Log irrigation sessions and track next watering schedules
        </p>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('farmer.planner.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:var(--radius-sm);border:1px solid var(--border);color:var(--text-secondary);text-decoration:none;font-size:0.875rem;transition:var(--transition);">
            <i data-lucide="calendar-days" style="width:14px;height:14px;"></i> Planner
        </a>
        <a href="{{ route('dashboard') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:var(--radius-sm);border:1px solid var(--border);color:var(--text-secondary);text-decoration:none;font-size:0.875rem;transition:var(--transition);">
            <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Dashboard
        </a>
    </div>
</div>

@if(session('success'))
    <div style="background:rgba(72,202,228,0.08);border:1px solid rgba(72,202,228,0.25);color:var(--sky);border-radius:var(--radius-md);padding:0.85rem 1.25rem;margin-bottom:1.25rem;font-size:0.875rem;display:flex;align-items:center;gap:8px;">
        <i data-lucide="check-circle" style="width:16px;height:16px;flex-shrink:0;"></i>
        {{ session('success') }}
    </div>
@endif

{{-- ─── Stats ─────────────────────────── --}}
<div class="irr-stats">
    <div class="irr-stat">
        <div class="irr-stat-label">Total Water Used</div>
        <div class="irr-stat-value">{{ number_format($totalWater, 0) }} L</div>
        <div class="irr-stat-sub">cumulative</div>
    </div>
    <div class="irr-stat">
        <div class="irr-stat-label">Sessions Logged</div>
        <div class="irr-stat-value" style="color:var(--green-bright);">{{ $totalSessions }}</div>
        <div class="irr-stat-sub">irrigation records</div>
    </div>
    <div class="irr-stat">
        <div class="irr-stat-label">Avg per Session</div>
        <div class="irr-stat-value" style="color:var(--amber);">
            {{ $totalSessions > 0 ? number_format($totalWater / $totalSessions, 0) : '—' }} L
        </div>
        <div class="irr-stat-sub">per irrigation</div>
    </div>
</div>

{{-- ─── Upcoming Reminder ─────────────────────────── --}}
@if($upcoming)
    @php $d = $upcoming->days_until_next; @endphp
    <div class="upcoming-banner">
        <i data-lucide="bell"></i>
        <span>
            Next irrigation for <strong>{{ $upcoming->field_name }}</strong>:
            @if($d === 0) <strong>Today!</strong>
            @elseif($d < 0) <strong style="color:var(--red-alert);">Overdue by {{ abs($d) }} day(s)!</strong>
            @else in <strong>{{ $d }} day(s)</strong> — {{ $upcoming->next_irrigation_date->format('d M Y') }}
            @endif
        </span>
    </div>
@endif

<div class="irr-layout">

    {{-- ─── Log Form ─────────────────────────────────── --}}
    <div class="form-card">
        <div class="form-card-header">
            <i data-lucide="plus-circle"></i>
            <h2>Log Irrigation</h2>
        </div>
        <form method="POST" action="{{ route('farmer.irrigation.store') }}" class="form-body">
            @csrf

            <div class="form-group">
                <label class="form-label">Field Name *</label>
                <input type="text" name="field_name" class="form-input"
                       placeholder="e.g. South Field, Khet 1"
                       value="{{ old('field_name') }}" required>
                @error('field_name')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            @if($plans->isNotEmpty())
            <div class="form-group">
                <label class="form-label">Link to Crop Plan (optional)</label>
                <select name="crop_plan_id" class="form-select">
                    <option value="">— None —</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('crop_plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->crop->emoji }} {{ $plan->crop->name }} — {{ $plan->field_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Method *</label>
                    <select name="method" class="form-select" required>
                        <option value="manual"    {{ old('method','manual') == 'manual'    ? 'selected':'' }}>🪣 Manual</option>
                        <option value="drip"      {{ old('method') == 'drip'      ? 'selected':'' }}>💧 Drip</option>
                        <option value="sprinkler" {{ old('method') == 'sprinkler' ? 'selected':'' }}>🌀 Sprinkler</option>
                        <option value="flood"     {{ old('method') == 'flood'     ? 'selected':'' }}>🌊 Flood</option>
                        <option value="furrow"    {{ old('method') == 'furrow'    ? 'selected':'' }}>〰️ Furrow</option>
                    </select>
                    @error('method')<span class="error-msg">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Date *</label>
                    <input type="date" name="irrigated_on" class="form-input"
                           max="{{ now()->format('Y-m-d') }}"
                           value="{{ old('irrigated_on', now()->format('Y-m-d')) }}" required>
                    @error('irrigated_on')<span class="error-msg">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Water Used (Litres) *</label>
                    <input type="number" name="water_used_liters" class="form-input"
                           placeholder="e.g. 500" min="1"
                           value="{{ old('water_used_liters') }}" required>
                    @error('water_used_liters')<span class="error-msg">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Duration (minutes) *</label>
                    <input type="number" name="duration_minutes" class="form-input"
                           placeholder="e.g. 30" min="1"
                           value="{{ old('duration_minutes') }}" required>
                    @error('duration_minutes')<span class="error-msg">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Next Irrigation Date</label>
                <input type="date" name="next_irrigation_date" class="form-input"
                       min="{{ now()->addDay()->format('Y-m-d') }}"
                       value="{{ old('next_irrigation_date') }}">
                <span style="font-size:0.75rem;color:var(--text-muted);">Leave blank if no reminder needed</span>
                @error('next_irrigation_date')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-textarea"
                          placeholder="Any observations…">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn-primary-irr">
                <i data-lucide="droplets" style="width:15px;height:15px;vertical-align:middle;margin-right:4px;"></i>
                Log Session
            </button>
        </form>
    </div>

    {{-- ─── Log List ─────────────────────────────────── --}}
    <div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
            <h2 style="font-family:var(--font-display);font-size:1.1rem;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:8px;">
                <i data-lucide="list" style="width:18px;height:18px;color:var(--sky);"></i>
                Session History ({{ $logs->total() }})
            </h2>
        </div>

        <div class="irr-table-card">
            @if($logs->isEmpty())
                <div style="padding:3rem;text-align:center;color:var(--text-muted);">
                    <i data-lucide="droplets" style="width:40px;height:40px;margin:0 auto 1rem;display:block;opacity:0.3;"></i>
                    <p>No irrigation sessions logged yet. Use the form to start tracking!</p>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Crop</th>
                            <th>Method</th>
                            <th>Water Used</th>
                            <th>Duration</th>
                            <th>Date</th>
                            <th>Next Irrigation</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td style="color:var(--text-primary);font-weight:500;">{{ $log->field_name }}</td>
                            <td>
                                @if($log->cropPlan)
                                    {{ $log->cropPlan->crop->emoji }} {{ $log->cropPlan->crop->name }}
                                @else
                                    <span style="color:var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td><span class="method-chip">{{ $log->method_label }}</span></td>
                            <td style="color:var(--sky);font-weight:600;">{{ number_format($log->water_used_liters, 0) }} L</td>
                            <td style="color:var(--text-muted);">{{ $log->duration_minutes }} min</td>
                            <td style="color:var(--text-muted);font-size:0.82rem;">{{ $log->irrigated_on->format('d M Y') }}</td>
                            <td>
                                @if($log->next_irrigation_date)
                                    @php $u = $log->urgency; @endphp
                                    <span class="next-{{ $u }}">
                                        @if($u === 'overdue') ⚠️ Overdue {{ abs($log->days_until_next) }}d
                                        @elseif($u === 'today') 💧 Today!
                                        @elseif($u === 'soon') 🔔 In {{ $log->days_until_next }}d
                                        @else {{ $log->next_irrigation_date->format('d M Y') }}
                                        @endif
                                    </span>
                                @else
                                    <span style="color:var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('farmer.irrigation.destroy', $log) }}" onsubmit="return confirm('Delete this log?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="del-btn"><i data-lucide="trash-2"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($logs->hasPages())
                <div class="pagination-wrap">
                    @if($logs->onFirstPage())
                        <span class="page-btn" style="opacity:0.4;">‹ Prev</span>
                    @else
                        <a href="{{ $logs->previousPageUrl() }}" class="page-btn">‹ Prev</a>
                    @endif

                    @foreach($logs->getUrlRange(max(1,$logs->currentPage()-2), min($logs->lastPage(),$logs->currentPage()+2)) as $page => $url)
                        <a href="{{ $url }}" class="page-btn {{ $page==$logs->currentPage()?'active':'' }}">{{ $page }}</a>
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
    </div>
</div>

@endsection