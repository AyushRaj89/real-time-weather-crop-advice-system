<?php
// File: app/Http/Controllers/CropPlannerController.php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\CropPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CropPlannerController extends Controller
{
    /**
     * GET /farmer/planner
     * List all crop plans for the authenticated farmer.
     */
    public function index()
    {
        $plans = Auth::user()
            ->cropPlans()
            ->with('crop')
            ->orderBy('planned_sow_date')
            ->get();

        $crops = Crop::where('is_active', true)->orderBy('name')->get();

        return view('farmer.planner.index', compact('plans', 'crops'));
    }

    /**
     * POST /farmer/planner
     * Create a new crop plan.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'crop_id'          => 'required|exists:crops,id',
            'field_name'       => 'required|string|max:100',
            'area_acres'       => 'required|numeric|min:0.1|max:9999',
            'planned_sow_date' => 'required|date|after_or_equal:today',
            'notes'            => 'nullable|string|max:500',
            'city'             => 'nullable|string|max:100',
        ]);

        $crop = Crop::findOrFail($data['crop_id']);

        $sowDate     = Carbon::parse($data['planned_sow_date']);
        $harvestDate = $crop->growth_days
            ? $sowDate->copy()->addDays($crop->growth_days)
            : null;

        Auth::user()->cropPlans()->create([
            ...$data,
            'expected_harvest_date' => $harvestDate,
            'status'                => 'planned',
        ]);

        return redirect()->route('farmer.planner.index')
            ->with('success', "Crop plan for {$crop->name} on \"{$data['field_name']}\" created!");
    }

    /**
     * PATCH /farmer/planner/{plan}
     * Update only the status of a plan (quick update buttons).
     */
    public function updateStatus(Request $request, CropPlan $plan)
    {
        abort_if($plan->user_id !== Auth::id(), 403);

        $request->validate([
            'status' => 'required|in:planned,sowing,growing,harvested,failed',
        ]);

        $plan->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Plan status updated.');
    }

    /**
     * PUT /farmer/planner/{plan}
     * Update a crop plan.
     */
    public function update(Request $request, CropPlan $plan)
    {
        abort_if($plan->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'crop_id'          => 'required|exists:crops,id',
            'field_name'       => 'required|string|max:100',
            'area_acres'       => 'required|numeric|min:0.1|max:9999',
            'planned_sow_date' => 'required|date',
            'status'           => 'nullable|in:planned,sowing,growing,harvested,failed',
            'notes'            => 'nullable|string|max:500',
            'city'             => 'nullable|string|max:100',
        ]);

        $crop = Crop::findOrFail($data['crop_id']);
        
        // Recalculate harvest date if sow date changed or crop changed
        $sowDate     = Carbon::parse($data['planned_sow_date']);
        $harvestDate = $crop->growth_days
            ? $sowDate->copy()->addDays($crop->growth_days)
            : null;

        $plan->update([
            ...$data,
            'expected_harvest_date' => $harvestDate,
        ]);

        return redirect()->route('farmer.planner.index')
            ->with('success', 'Crop plan updated successfully.');
    }

    /**
     * DELETE /farmer/planner/{plan}
     * Remove a crop plan.
     */
    public function destroy(CropPlan $plan)
    {
        abort_if($plan->user_id !== Auth::id(), 403);
        $plan->delete();

        return redirect()->route('farmer.planner.index')
            ->with('success', 'Crop plan removed.');
    }
}