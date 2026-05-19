
@extends('layouts.app')
@section('title', 'Crop Rules')

@push('styles')
<style>
.rule-conditions {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
}

.cond-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 8px;
    background: var(--bg-base);
    border: 1px solid var(--border);
    border-radius: 100px;
    font-size: 0.7rem;
    color: var(--text-secondary);
    white-space: nowrap;
}

.priority-bar {
    display: flex;
    align-items: center;
    gap: 8px;
}

.priority-dots {
    display: flex;
    gap: 3px;
}

.dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: var(--border);
}

.dot.filled { background: var(--green-bright); }
</style>
@endpush

@section('content')
<div class="page-wrapper">

    <div class="page-header">
        <div>
            <h1>⚙️ Crop Rules</h1>
            <p>Define which crops are recommended under specific weather conditions</p>
        </div>
        <a href="{{ route('admin.rules.create') }}" class="btn btn-primary">
            <i data-lucide="plus"></i> Add Rule
        </a>
    </div>

    {{-- Filter by crop --}}
    <div style="margin-bottom:1.25rem;display:flex;gap:8px;flex-wrap:wrap;">
        <span style="font-size:0.82rem;color:var(--text-muted);align-self:center;">
            {{ $rules->count() }} total rules
        </span>
    </div>

    <div class="card" style="padding:0;overflow:hidden;">
        @if($rules->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>Crop</th>
                    <th>Conditions</th>
                    <th>Season</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rules as $rule)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="font-size:1.1rem;">{{ $rule->crop->emoji }}</span>
                            <div>
                                <div style="font-weight:600;">{{ $rule->crop->name }}</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">{{ $rule->crop->local_name }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="rule-conditions">
                            @if($rule->min_temperature !== null || $rule->max_temperature !== null)
                            <span class="cond-pill">🌡️
                                {{ $rule->min_temperature !== null ? $rule->min_temperature.'°' : '?' }}
                                –
                                {{ $rule->max_temperature !== null ? $rule->max_temperature.'°' : '?' }}
                            </span>
                            @endif
                            @if($rule->min_humidity !== null || $rule->max_humidity !== null)
                            <span class="cond-pill">💧
                                {{ $rule->min_humidity ?? '?' }}–{{ $rule->max_humidity ?? '?' }}%
                            </span>
                            @endif
                            @if($rule->min_rainfall !== null || $rule->max_rainfall !== null)
                            <span class="cond-pill">🌧️
                                {{ $rule->min_rainfall ?? '0' }}–{{ $rule->max_rainfall ?? '∞' }}mm
                            </span>
                            @endif
                            @if($rule->weather_condition)
                            <span class="cond-pill">☁️ {{ $rule->weather_condition }}</span>
                            @endif
                            @if(!$rule->min_temperature && !$rule->min_humidity && !$rule->min_rainfall && !$rule->weather_condition)
                            <span style="color:var(--text-muted);font-size:0.78rem;">Any conditions</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($rule->season)
                        <span class="badge badge-green">{{ $rule->season }}</span>
                        @else
                        <span style="color:var(--text-muted);font-size:0.8rem;">Any</span>
                        @endif
                    </td>
                    <td>
                        <div class="priority-bar">
                            <div class="priority-dots">
                                @for($i = 1; $i <= 10; $i++)
                                <div class="dot {{ $i <= $rule->priority ? 'filled' : '' }}"></div>
                                @endfor
                            </div>
                            <span style="font-size:0.78rem;color:var(--text-muted);">{{ $rule->priority }}/10</span>
                        </div>
                    </td>
                    <td>
                        @if($rule->is_active)
                        <span class="badge badge-green">Active</span>
                        @else
                        <span class="badge badge-red">Off</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.rules.edit', $rule) }}" class="btn btn-secondary btn-sm">
                                <i data-lucide="pencil"></i>
                            </a>
                            <form action="{{ route('admin.rules.destroy', $rule) }}" method="POST"
                                  onsubmit="return confirm('Delete this rule?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i data-lucide="trash-2"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="text-align:center;padding:3rem;color:var(--text-muted);">
            <div style="font-size:3rem;margin-bottom:1rem;">⚙️</div>
            <p>No rules defined yet. <a href="{{ route('admin.rules.create') }}" style="color:var(--green-bright);">Create your first rule →</a></p>
        </div>
        @endif
    </div>

    <div style="margin-top:1.5rem;display:flex;gap:0.75rem;">
        <a href="{{ route('admin.crops.index') }}" class="btn btn-secondary">
            <i data-lucide="leaf"></i> Manage Crops
        </a>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i data-lucide="arrow-left"></i> Dashboard
        </a>
    </div>

</div>
@endsection