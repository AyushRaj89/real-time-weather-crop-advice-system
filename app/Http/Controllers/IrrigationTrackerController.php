<?php
// File: app/Http/Controllers/IrrigationTrackerController.php

namespace App\Http\Controllers;

use App\Models\IrrigationLog;
use App\Models\CropPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IrrigationTrackerController extends Controller
{
    /**
     * GET /farmer/irrigation
     */
    public function index()
    {
        $logs = Auth::user()
            ->irrigationLogs()
            ->with('cropPlan.crop')
            ->orderByDesc('irrigated_on')
            ->paginate(15);

        // Active crop plans for the dropdown
        $plans = Auth::user()
            ->cropPlans()
            ->whereIn('status', ['sowing', 'growing'])
            ->with('crop')
            ->orderBy('field_name')
            ->get();

        // Summary stats
        $totalWater    = Auth::user()->irrigationLogs()->sum('water_used_liters');
        $totalSessions = Auth::user()->irrigationLogs()->count();
        $upcoming      = Auth::user()->irrigationLogs()
            ->whereNotNull('next_irrigation_date')
            ->whereDate('next_irrigation_date', '>=', now())
            ->orderBy('next_irrigation_date')
            ->first();

        return view('farmer.irrigation.index', compact(
            'logs', 'plans', 'totalWater', 'totalSessions', 'upcoming'
        ));
    }

    /**
     * POST /farmer/irrigation
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'field_name'           => 'required|string|max:100',
            'crop_plan_id'         => 'nullable|exists:crop_plans,id',
            'method'               => 'required|in:drip,sprinkler,flood,furrow,manual',
            'water_used_liters'    => 'required|numeric|min:1',
            'duration_minutes'     => 'required|integer|min:1',
            'irrigated_on'         => 'required|date',
            'next_irrigation_date' => 'nullable|date|after:irrigated_on',
            'notes'                => 'nullable|string|max:500',
        ]);

        // Validate the crop_plan_id belongs to this user
        if (!empty($data['crop_plan_id'])) {
            $plan = CropPlan::find($data['crop_plan_id']);
            if (!$plan || $plan->user_id !== Auth::id()) {
                return back()->withErrors(['crop_plan_id' => 'Invalid crop plan.'])->withInput();
            }
        }

        Auth::user()->irrigationLogs()->create([
            ...$data,
            'next_reminder_set' => !empty($data['next_irrigation_date']),
        ]);

        return redirect()->route('farmer.irrigation.index')
            ->with('success', 'Irrigation session logged successfully.');
    }

    /**
     * DELETE /farmer/irrigation/{log}
     */
    public function destroy(IrrigationLog $log)
    {
        abort_if($log->user_id !== Auth::id(), 403);
        $log->delete();

        return redirect()->route('farmer.irrigation.index')
            ->with('success', 'Irrigation log deleted.');
    }
}