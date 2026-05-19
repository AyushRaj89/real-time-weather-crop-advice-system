
@extends('layouts.app')
@section('title', 'Add Crop')

@section('content')
<div class="page-wrapper" style="max-width:680px;">

    <div class="page-header">
        <div>
            <h1>🌿 Add New Crop</h1>
            <p>Define a crop that can be recommended to farmers</p>
        </div>
        <a href="{{ route('admin.crops.index') }}" class="btn btn-secondary">
            <i data-lucide="arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <form action="{{ route('admin.crops.store') }}" method="POST">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Crop Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Rice" required>
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Local Name</label>
                    <input type="text" name="local_name" class="form-control" value="{{ old('local_name') }}" placeholder="e.g. Chawal / Paddy">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Brief description of the crop and growing conditions…">{{ old('description') }}</textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Growing Season</label>
                    <select name="growing_season" class="form-control">
                        <option value="">— Select —</option>
                        @foreach(['Kharif','Rabi','All'] as $s)
                        <option value="{{ $s }}" {{ old('growing_season') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Growth Days</label>
                    <input type="number" name="growth_days" class="form-control" value="{{ old('growth_days') }}" placeholder="e.g. 120" min="1">
                </div>
                <div class="form-group">
                    <label class="form-label">Water Requirement</label>
                    <select name="water_requirement" class="form-control">
                        <option value="">— Select —</option>
                        @foreach(['Low','Medium','High'] as $w)
                        <option value="{{ $w }}" {{ old('water_requirement') == $w ? 'selected' : '' }}>{{ $w }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                        style="width:16px;height:16px;accent-color:var(--green-mid);">
                    <span style="font-size:0.875rem;color:var(--text-secondary);">Active (appears in recommendations)</span>
                </label>
            </div>

            <hr>
            <div style="display:flex;justify-content:flex-end;gap:0.75rem;">
                <a href="{{ route('admin.crops.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="check"></i> Save Crop
                </button>
            </div>
        </form>
    </div>
</div>
@endsection