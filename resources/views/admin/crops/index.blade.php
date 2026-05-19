
@extends('layouts.app')
@section('title', 'Manage Crops')

@section('content')
<div class="page-wrapper">

    <div class="page-header">
        <div>
            <h1>🌿 Crop Management</h1>
            <p>Add and configure crops that appear in recommendations</p>
        </div>
        <a href="{{ route('admin.crops.create') }}" class="btn btn-primary">
            <i data-lucide="plus"></i> Add Crop
        </a>
    </div>

    <div class="card">
        @if($crops->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>Crop</th>
                    <th>Local Name</th>
                    <th>Season</th>
                    <th>Water Need</th>
                    <th>Growth</th>
                    <th>Rules</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($crops as $crop)
                <tr>
                    <td>
                        <span style="font-size:1.1rem;margin-right:6px;">{{ $crop->emoji }}</span>
                        {{ $crop->name }}
                    </td>
                    <td>{{ $crop->local_name ?? '—' }}</td>
                    <td>{{ $crop->growing_season ?? '—' }}</td>
                    <td>
                        @php
                            $wc = ['Low'=>'badge-amber','Medium'=>'badge-sky','High'=>'badge-green'];
                        @endphp
                        @if($crop->water_requirement)
                        <span class="badge {{ $wc[$crop->water_requirement] ?? 'badge-muted' }}">
                            {{ $crop->water_requirement }}
                        </span>
                        @else —
                        @endif
                    </td>
                    <td>{{ $crop->growth_days ? $crop->growth_days . ' days' : '—' }}</td>
                    <td>
                        <span class="badge badge-muted">{{ $crop->rules_count }} rules</span>
                    </td>
                    <td>
                        @if($crop->is_active)
                        <span class="badge badge-green">Active</span>
                        @else
                        <span class="badge badge-red">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.crops.edit', $crop) }}" class="btn btn-secondary btn-sm">
                                <i data-lucide="pencil"></i> Edit
                            </a>
                            <form action="{{ route('admin.crops.destroy', $crop) }}" method="POST"
                                  onsubmit="return confirm('Delete {{ $crop->name }}? This also deletes its rules.')">
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
            <div style="font-size:3rem;margin-bottom:1rem;">🌱</div>
            <p>No crops added yet. <a href="{{ route('admin.crops.create') }}" style="color:var(--green-bright);">Add your first crop →</a></p>
        </div>
        @endif
    </div>

    <div style="margin-top:1.5rem;display:flex;gap:0.75rem;">
        <a href="{{ route('admin.rules.index') }}" class="btn btn-secondary">
            <i data-lucide="sliders-horizontal"></i> Manage Rules
        </a>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i data-lucide="arrow-left"></i> Back to Dashboard
        </a>
    </div>

</div>
@endsection