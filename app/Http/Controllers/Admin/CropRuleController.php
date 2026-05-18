<?php
// File: app/Http/Controllers/Admin/CropRuleController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use App\Models\CropRule;
use Illuminate\Http\Request;

class CropRuleController extends Controller
{
    // ─── Crops CRUD ────────────────────────────────────────────

    public function cropsIndex()
    {
        $crops = Crop::withCount('rules')->latest()->get();
        return view('admin.crops.index', compact('crops'));
    }

    public function cropsCreate()
    {
        return view('admin.crops.create');
    }

    public function cropsStore(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:100',
            'local_name'        => 'nullable|string|max:100',
            'description'       => 'nullable|string',
            'growing_season'    => 'nullable|string|max:50',
            'growth_days'       => 'nullable|integer|min:1',
            'water_requirement' => 'nullable|in:Low,Medium,High',
            'is_active'         => 'boolean',
        ]);

        Crop::create($validated);

        return redirect()->route('admin.crops.index')
            ->with('success', 'Crop added successfully.');
    }

    public function cropsEdit(Crop $crop)
    {
        return view('admin.crops.edit', compact('crop'));
    }

    public function cropsUpdate(Request $request, Crop $crop)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:100',
            'local_name'        => 'nullable|string|max:100',
            'description'       => 'nullable|string',
            'growing_season'    => 'nullable|string|max:50',
            'growth_days'       => 'nullable|integer|min:1',
            'water_requirement' => 'nullable|in:Low,Medium,High',
            'is_active'         => 'boolean',
        ]);

        $crop->update($validated);

        return redirect()->route('admin.crops.index')
            ->with('success', 'Crop updated successfully.');
    }

    public function cropsDestroy(Crop $crop)
    {
        $crop->delete();
        return redirect()->route('admin.crops.index')
            ->with('success', 'Crop deleted.');
    }

    // ─── Rules CRUD ────────────────────────────────────────────

    public function rulesIndex()
    {
        $rules = CropRule::with('crop')->latest()->get();
        $crops = Crop::where('is_active', true)->get();
        return view('admin.rules.index', compact('rules', 'crops'));
    }

    public function rulesCreate()
    {
        $crops = Crop::where('is_active', true)->orderBy('name')->get();
        return view('admin.rules.create', compact('crops'));
    }

    public function rulesStore(Request $request)
    {
        $validated = $request->validate([
            'crop_id'           => 'required|exists:crops,id',
            'min_temperature'   => 'nullable|numeric|between:-50,60',
            'max_temperature'   => 'nullable|numeric|between:-50,60',
            'min_humidity'      => 'nullable|integer|between:0,100',
            'max_humidity'      => 'nullable|integer|between:0,100',
            'min_rainfall'      => 'nullable|numeric|min:0',
            'max_rainfall'      => 'nullable|numeric|min:0',
            'weather_condition' => 'nullable|string|max:50',
            'season'            => 'nullable|in:Summer,Winter,Monsoon,Spring,All',
            'priority'          => 'integer|min:1|max:10',
            'advice'            => 'nullable|string|max:500',
            'is_active'         => 'boolean',
        ]);

        CropRule::create($validated);

        return redirect()->route('admin.rules.index')
            ->with('success', 'Rule created successfully.');
    }

    public function rulesEdit(CropRule $rule)
    {
        $crops = Crop::where('is_active', true)->orderBy('name')->get();
        return view('admin.rules.edit', compact('rule', 'crops'));
    }

    public function rulesUpdate(Request $request, CropRule $rule)
    {
        $validated = $request->validate([
            'crop_id'           => 'required|exists:crops,id',
            'min_temperature'   => 'nullable|numeric|between:-50,60',
            'max_temperature'   => 'nullable|numeric|between:-50,60',
            'min_humidity'      => 'nullable|integer|between:0,100',
            'max_humidity'      => 'nullable|integer|between:0,100',
            'min_rainfall'      => 'nullable|numeric|min:0',
            'max_rainfall'      => 'nullable|numeric|min:0',
            'weather_condition' => 'nullable|string|max:50',
            'season'            => 'nullable|in:Summer,Winter,Monsoon,Spring,All',
            'priority'          => 'integer|min:1|max:10',
            'advice'            => 'nullable|string|max:500',
            'is_active'         => 'boolean',
        ]);

        $rule->update($validated);

        return redirect()->route('admin.rules.index')
            ->with('success', 'Rule updated successfully.');
    }

    public function rulesDestroy(CropRule $rule)
    {
        $rule->delete();
        return redirect()->route('admin.rules.index')
            ->with('success', 'Rule deleted.');
    }
}