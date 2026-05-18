{{-- File: resources/views/admin/rules/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Add Rule')

@push('styles')
<style>
.rule-section {
    background: var(--bg-base);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 1.25rem;
    margin-bottom: 1.25rem;
}

.rule-section-title {
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 6px;
}

.range-row {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    gap: 8px;
}

.range-sep {
    font-size: 0.8rem;
    color: var(--text-muted);
    text-align: center;
    padding-top: 22px;
}
</style>
@endpush

@section('content')
<div class="page-wrapper" style="max-width:720px;">

    <div class="page-header">
        <div>
            <h1>⚙️ Add Crop Rule</h1>
            <p>Define when a crop should be recommended based on weather</p>
        </div>
        <a href="{{ route('admin.rules.index') }}" class="btn btn-secondary">
            <i data-lucide="arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom:1.25rem;">
            @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
        </div>
        @endif

        <form action="{{ route('admin.rules.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Crop *</label>
                <select name="crop_id" class="form-control" required>
                    <option value="">— Select a crop —</option>
                    @foreach($crops as $crop)
                    <option value="{{ $crop->id }}" {{ old('crop_id') == $crop->id ? 'selected' : '' }}>
                        {{ $crop->name }} ({{ $crop->local_name }})
                    </option>
                    @endforeach
                </select>
                @error('crop_id') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            {{-- Temperature --}}
            <div class="rule-section">
                <div class="rule-section-title">🌡️ Temperature Conditions (°C)</div>
                <div class="range-row">
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Min Temp</label>
                        <input type="number" name="min_temperature" class="form-control" value="{{ old('min_temperature') }}" placeholder="e.g. 20" step="0.5">
                    </div>
                    <div class="range-sep">to</div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Max Temp</label>
                        <input type="number" name="max_temperature" class="form-control" value="{{ old('max_temperature') }}" placeholder="e.g. 35" step="0.5">
                    </div>
                </div>
                <div style="font-size:0.75rem;color:var(--text-muted);margin-top:6px;">Leave blank to not restrict by temperature</div>
            </div>

            {{-- Humidity --}}
            <div class="rule-section">
                <div class="rule-section-title">💧 Humidity Conditions (%)</div>
                <div class="range-row">
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Min Humidity</label>
                        <input type="number" name="min_humidity" class="form-control" value="{{ old('min_humidity') }}" placeholder="e.g. 60" min="0" max="100">
                    </div>
                    <div class="range-sep">to</div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Max Humidity</label>
                        <input type="number" name="max_humidity" class="form-control" value="{{ old('max_humidity') }}" placeholder="e.g. 90" min="0" max="100">
                    </div>
                </div>
            </div>

            {{-- Rainfall --}}
            <div class="rule-section">
                <div class="rule-section-title">🌧️ Rainfall Conditions (mm/hr)</div>
                <div class="range-row">
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Min Rainfall</label>
                        <input type="number" name="min_rainfall" class="form-control" value="{{ old('min_rainfall') }}" placeholder="e.g. 2" step="0.1" min="0">
                    </div>
                    <div class="range-sep">to</div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Max Rainfall</label>
                        <input type="number" name="max_rainfall" class="form-control" value="{{ old('max_rainfall') }}" placeholder="e.g. 20" step="0.1" min="0">
                    </div>
                </div>
            </div>

            {{-- Other conditions --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Weather Condition</label>
                    <select name="weather_condition" class="form-control">
                        <option value="">— Any —</option>
                        @foreach(['Clear','Clouds','Rain','Drizzle','Thunderstorm','Snow','Mist','Fog'] as $wc)
                        <option value="{{ $wc }}" {{ old('weather_condition') == $wc ? 'selected' : '' }}>{{ $wc }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Season</label>
                    <select name="season" class="form-control">
                        <option value="">— Any —</option>
                        @foreach(['Summer','Winter','Monsoon','Spring','All'] as $s)
                        <option value="{{ $s }}" {{ old('season') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Advice Message</label>
                <textarea name="advice" class="form-control" rows="2"
                    placeholder="e.g. High humidity detected – ideal conditions for rice cultivation. Ensure standing water.">{{ old('advice') }}</textarea>
                <div style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">Shown to the farmer when this rule matches</div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Priority (1–10)</label>
                    <input type="number" name="priority" class="form-control" value="{{ old('priority', 5) }}" min="1" max="10">
                    <div style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">Higher priority = shown first</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin-top:10px;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                            style="width:16px;height:16px;accent-color:var(--green-mid);">
                        <span style="font-size:0.875rem;color:var(--text-secondary);">Active</span>
                    </label>
                </div>
            </div>

            <hr>
            <div style="display:flex;justify-content:flex-end;gap:0.75rem;">
                <a href="{{ route('admin.rules.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="check"></i> Save Rule
                </button>
            </div>
        </form>
    </div>
</div>
@endsection