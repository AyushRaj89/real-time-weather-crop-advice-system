{{-- File: resources/views/farmer/planner/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Crop Planner')

@push('styles')
<style>
/* ─── Layout ───── */
.planner-layout {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 1.25rem;
    align-items: start;
}

/* ─── Form Card ───── */
.form-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    position: sticky;
    top: 80px;
}

.form-card-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--border);
    background: var(--bg-card-2);
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-card-header h2 {
    font-family: var(--font-display);
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-primary);
}

.form-card-header i { width: 16px; height: 16px; color: var(--green-bright); }

.form-body { padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem; }

.form-group { display: flex; flex-direction: column; gap: 6px; }

.form-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.form-input, .form-select, .form-textarea {
    padding: 10px 14px;
    background: var(--bg-base);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text-primary);
    font-family: var(--font-body);
    font-size: 0.875rem;
    outline: none;
    transition: var(--transition);
    width: 100%;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
    border-color: var(--green-mid);
    box-shadow: 0 0 0 3px rgba(64,145,108,0.12);
}

.form-select option { background: var(--bg-card); }

.form-textarea { resize: vertical; min-height: 80px; }

.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

.btn-primary {
    padding: 11px;
    background: linear-gradient(135deg, var(--green-mid), var(--green-bright));
    border: none;
    border-radius: var(--radius-sm);
    color: #0a0f0d;
    font-family: var(--font-display);
    font-size: 0.9rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
    width: 100%;
}

.btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }

.error-msg { font-size: 0.78rem; color: var(--red-alert); }

/* ─── Plans List ───── */
.plans-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.plans-header h2 {
    font-family: var(--font-display);
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 8px;
}

.plans-header h2 i { width: 18px; height: 18px; color: var(--green-bright); }

.filter-tabs {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.tab-btn {
    padding: 4px 12px;
    border-radius: 16px;
    border: 1px solid var(--border);
    background: transparent;
    color: var(--text-muted);
    font-size: 0.78rem;
    cursor: pointer;
    transition: var(--transition);
}

.tab-btn.active, .tab-btn:hover {
    background: rgba(82,183,136,0.1);
    border-color: var(--green-mid);
    color: var(--green-bright);
}

/* ─── Plan Card ───── */
.plan-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.25rem 1.5rem;
    margin-bottom: 1rem;
    transition: var(--transition);
}

.plan-card:hover { border-color: var(--border-light); }

.plan-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1rem;
}

.plan-crop {
    display: flex;
    align-items: center;
    gap: 10px;
}

.plan-crop-emoji { font-size: 1.6rem; }

.plan-crop-name {
    font-family: var(--font-display);
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-primary);
}

.plan-field {
    font-size: 0.82rem;
    color: var(--text-muted);
    margin-top: 2px;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 16px;
    font-size: 0.78rem;
    font-weight: 600;
    white-space: nowrap;
}

.status-planned   { background: rgba(255,209,102,0.1); color: var(--gold);  border: 1px solid rgba(255,209,102,0.2); }
.status-sowing    { background: rgba(116,198,157,0.1); color: var(--green-glow); border: 1px solid rgba(116,198,157,0.2); }
.status-growing   { background: rgba(82,183,136,0.12); color: var(--green-bright); border: 1px solid rgba(82,183,136,0.25); }
.status-harvested { background: rgba(72,202,228,0.1); color: var(--sky); border: 1px solid rgba(72,202,228,0.2); }
.status-failed    { background: rgba(230,57,70,0.1); color: var(--red-alert); border: 1px solid rgba(230,57,70,0.2); }

/* ─── Progress Bar ───── */
.progress-wrap { margin: 1rem 0 0.5rem; }

.progress-meta {
    display: flex;
    justify-content: space-between;
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-bottom: 6px;
}

.progress-bar {
    height: 6px;
    background: var(--bg-base);
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    border-radius: 3px;
    background: linear-gradient(90deg, var(--green-mid), var(--green-bright));
    transition: width 0.5s ease;
}

/* ─── Plan Meta Row ───── */
.plan-meta {
    display: flex;
    gap: 1.25rem;
    margin-top: 0.75rem;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.8rem;
    color: var(--text-muted);
}

.meta-item i { width: 13px; height: 13px; }

.meta-item strong { color: var(--text-secondary); }

/* ─── Plan Actions ───── */
.plan-actions {
    display: flex;
    gap: 6px;
    margin-top: 1rem;
    flex-wrap: wrap;
    align-items: center;
}

.action-form { display: inline; }

.status-select-btn {
    padding: 5px 12px;
    border-radius: 16px;
    border: 1px solid var(--border);
    background: transparent;
    color: var(--text-muted);
    font-size: 0.78rem;
    cursor: pointer;
    transition: var(--transition);
}

.status-select-btn:hover { border-color: var(--green-mid); color: var(--green-bright); }

.delete-plan-btn {
    margin-left: auto;
    padding: 5px 12px;
    border-radius: 16px;
    border: 1px solid rgba(230,57,70,0.2);
    background: transparent;
    color: var(--red-alert);
    font-size: 0.78rem;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.delete-plan-btn:hover { background: rgba(230,57,70,0.1); }
.delete-plan-btn i { width: 12px; height: 12px; }

.harvest-badge {
    font-size: 0.75rem;
    padding: 2px 8px;
    border-radius: 12px;
    background: rgba(255,209,102,0.1);
    color: var(--gold);
    border: 1px solid rgba(255,209,102,0.2);
}

.harvest-soon { background: rgba(230,57,70,0.1); color: var(--red-alert); border-color: rgba(230,57,70,0.2); }

.empty-state {
    text-align: center;
    padding: 3rem;
    color: var(--text-muted);
}

.empty-state i { width: 48px; height: 48px; margin: 0 auto 1rem; display: block; opacity: 0.3; }

@media (max-width: 900px) {
    .planner-layout { grid-template-columns: 1fr; }
    .form-card { position: static; }
}
</style>
@endpush

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-family:var(--font-display);font-size:1.6rem;font-weight:800;color:var(--text-primary);">
            <i data-lucide="calendar-days" style="width:22px;height:22px;vertical-align:middle;margin-right:8px;color:var(--green-bright);"></i>
            Crop Planner
        </h1>
        <p style="color:var(--text-muted);font-size:0.875rem;margin-top:4px;">
            Schedule your crops, track growth progress and harvest dates
        </p>
    </div>
    <a href="{{ route('dashboard') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:var(--radius-sm);border:1px solid var(--border);color:var(--text-secondary);text-decoration:none;font-size:0.875rem;transition:var(--transition);">
        <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Dashboard
    </a>
</div>

@if(session('success'))
    <div style="background:rgba(82,183,136,0.1);border:1px solid var(--green-mid);color:var(--green-bright);border-radius:var(--radius-md);padding:0.85rem 1.25rem;margin-bottom:1.25rem;font-size:0.875rem;display:flex;align-items:center;gap:8px;">
        <i data-lucide="check-circle" style="width:16px;height:16px;flex-shrink:0;"></i>
        {{ session('success') }}
    </div>
@endif

<div class="planner-layout">

    {{-- ─── Add Plan Form ─────────────────────────────── --}}
    <div class="form-card">
        <div class="form-card-header">
            <i data-lucide="plus-circle"></i>
            <h2>Add New Plan</h2>
        </div>
        <form method="POST" action="{{ route('farmer.planner.store') }}" class="form-body">
            @csrf

            <div class="form-group">
                <label class="form-label">Crop *</label>
                <select name="crop_id" class="form-select" required>
                    <option value="">— Select a crop —</option>
                    @foreach($crops as $crop)
                        <option value="{{ $crop->id }}" {{ old('crop_id') == $crop->id ? 'selected' : '' }}>
                            {{ $crop->emoji }} {{ $crop->name }}
                            @if($crop->local_name) ({{ $crop->local_name }}) @endif
                        </option>
                    @endforeach
                </select>
                @error('crop_id')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Field Name *</label>
                <input type="text" name="field_name" class="form-input"
                       placeholder="e.g. North Field, Khet 2"
                       value="{{ old('field_name') }}" required>
                @error('field_name')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Area (Acres) *</label>
                    <input type="number" name="area_acres" class="form-input"
                           placeholder="e.g. 2.5" step="0.1" min="0.1"
                           value="{{ old('area_acres') }}" required>
                    @error('area_acres')<span class="error-msg">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">City (optional)</label>
                    <input type="text" name="city" class="form-input"
                           placeholder="Your city"
                           value="{{ old('city', Auth::user()->default_city) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Planned Sow Date *</label>
                <input type="date" name="planned_sow_date" class="form-input"
                       min="{{ now()->format('Y-m-d') }}"
                       value="{{ old('planned_sow_date') }}" required>
                <span style="font-size:0.75rem;color:var(--text-muted);">Harvest date auto-calculated from crop growth days</span>
                @error('planned_sow_date')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-textarea"
                          placeholder="Any notes about this crop plan…">{{ old('notes') }}</textarea>
                @error('notes')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="btn-primary">
                <i data-lucide="plus" style="width:15px;height:15px;vertical-align:middle;margin-right:4px;"></i>
                Add Crop Plan
            </button>
        </form>
    </div>

    {{-- ─── Plans List ─────────────────────────────────── --}}
    <div>
        <div class="plans-header">
            <h2><i data-lucide="sprout"></i> My Plans ({{ $plans->count() }})</h2>
            <div class="filter-tabs">
                <button class="tab-btn active" onclick="filterPlans('all', this)">All</button>
                <button class="tab-btn" onclick="filterPlans('planned', this)">Planned</button>
                <button class="tab-btn" onclick="filterPlans('growing', this)">Growing</button>
                <button class="tab-btn" onclick="filterPlans('harvested', this)">Harvested</button>
            </div>
        </div>

        @if($plans->isEmpty())
            <div class="plan-card empty-state">
                <i data-lucide="calendar-x"></i>
                <p>No crop plans yet. Add your first plan using the form!</p>
            </div>
        @else
            @foreach($plans as $plan)
            <div class="plan-card" data-status="{{ $plan->status }}">
                <div class="plan-top">
                    <div class="plan-crop">
                        <span class="plan-crop-emoji">{{ $plan->crop->emoji }}</span>
                        <div>
                            <div class="plan-crop-name">{{ $plan->crop->name }}
                                @if($plan->crop->local_name)
                                    <span style="font-size:0.8rem;color:var(--text-muted);font-family:var(--font-body);font-weight:400;">({{ $plan->crop->local_name }})</span>
                                @endif
                            </div>
                            <div class="plan-field">
                                <i data-lucide="map-pin" style="width:11px;height:11px;vertical-align:middle;"></i>
                                {{ $plan->field_name }}
                                @if($plan->city) · {{ $plan->city }} @endif
                            </div>
                        </div>
                    </div>

                    <span class="status-badge {{ $plan->status_color }}">{{ $plan->status_label }}</span>
                </div>

                {{-- Progress Bar --}}
                @if(in_array($plan->status, ['sowing', 'growing']))
                <div class="progress-wrap">
                    <div class="progress-meta">
                        <span>Growth progress</span>
                        <span>{{ $plan->progress }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width:{{ $plan->progress }}%;"></div>
                    </div>
                </div>
                @endif

                {{-- Meta Info --}}
                <div class="plan-meta">
                    <span class="meta-item">
                        <i data-lucide="calendar"></i>
                        Sow: <strong>{{ $plan->planned_sow_date->format('d M Y') }}</strong>
                    </span>
                    @if($plan->expected_harvest_date)
                    <span class="meta-item">
                        <i data-lucide="wheat"></i>
                        Harvest: <strong>{{ $plan->expected_harvest_date->format('d M Y') }}</strong>
                    </span>
                    @endif
                    <span class="meta-item">
                        <i data-lucide="ruler"></i>
                        <strong>{{ $plan->area_acres }} acres</strong>
                    </span>
                    @if($plan->days_to_harvest !== null && !in_array($plan->status, ['harvested','failed','planned']))
                        @php $d = $plan->days_to_harvest; @endphp
                        <span class="harvest-badge {{ $d <= 7 ? 'harvest-soon' : '' }}">
                            {{ $d > 0 ? "🌾 {$d} days to harvest" : ($d == 0 ? '🌾 Harvest today!' : '⚠️ Overdue by '.abs($d).' days') }}
                        </span>
                    @endif
                </div>

                @if($plan->notes)
                <p style="font-size:0.8rem;color:var(--text-muted);margin-top:0.75rem;padding-top:0.75rem;border-top:1px solid var(--border);">
                    📝 {{ $plan->notes }}
                </p>
                @endif

                {{-- Actions --}}
                <div class="plan-actions">
                    @if($plan->status !== 'harvested' && $plan->status !== 'failed')
                        @php
                            $nextStatuses = match($plan->status) {
                                'planned'  => ['sowing' => '🌱 Mark Sowing'],
                                'sowing'   => ['growing' => '🌿 Mark Growing'],
                                'growing'  => ['harvested' => '✅ Mark Harvested', 'failed' => '❌ Mark Failed'],
                                default    => [],
                            };
                        @endphp
                        @foreach($nextStatuses as $status => $label)
                            <form method="POST" action="{{ route('farmer.planner.status', $plan) }}" class="action-form">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="{{ $status }}">
                                <button type="submit" class="status-select-btn">{{ $label }}</button>
                            </form>
                        @endforeach
                    @endif

                    <form method="POST" action="{{ route('farmer.planner.destroy', $plan) }}" class="action-form" onsubmit="return confirm('Delete this plan?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="delete-plan-btn">
                            <i data-lucide="trash-2"></i> Remove
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
function filterPlans(status, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    document.querySelectorAll('.plan-card[data-status]').forEach(card => {
        card.style.display = (status === 'all' || card.dataset.status === status) ? '' : 'none';
    });
}
</script>
@endpush