<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\CropRuleController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\CropPlannerController;
use App\Http\Controllers\IrrigationTrackerController;

// ─── Public routes ────────────────────────────────────────────────────────────

Route::get('/', fn() => redirect()->route('dashboard'));

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ─── Authenticated user routes ────────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    // Main dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/city', [DashboardController::class, 'saveCity'])->name('dashboard.city');

    // Farmer routes
    Route::prefix('farmer')->name('farmer.')->group(function () {
        Route::get('/planner', [CropPlannerController::class, 'index'])->name('planner.index');
        Route::post('/planner', [CropPlannerController::class, 'store'])->name('planner.store');
        Route::patch('/planner/{plan}', [CropPlannerController::class, 'updateStatus'])->name('planner.status');
        Route::put('/planner/{plan}', [CropPlannerController::class, 'update'])->name('planner.update');
        Route::delete('/planner/{plan}', [CropPlannerController::class, 'destroy'])->name('planner.destroy');

        Route::get('/irrigation', [IrrigationTrackerController::class, 'index'])->name('irrigation.index');
        Route::post('/irrigation', [IrrigationTrackerController::class, 'store'])->name('irrigation.store');
        Route::delete('/irrigation/{log}', [IrrigationTrackerController::class, 'destroy'])->name('irrigation.destroy');
    });

    // Weather API endpoints
    Route::prefix('weather')->name('weather.')->group(function () {
        Route::get('/fetch',            [WeatherController::class, 'fetch'])->name('fetch');
        Route::get('/json',             [WeatherController::class, 'json'])->name('json');
        Route::get('/history',          [WeatherController::class, 'history'])->name('history');
        Route::delete('/history/{log}', [WeatherController::class, 'destroyLog'])->name('log.destroy');
    });
});

// ─── Admin routes ─────────────────────────────────────────────────────────────

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', fn() => redirect()->route('admin.crops.index'))->name('home');

    // Crop management
    Route::get('/crops', [CropRuleController::class, 'cropsIndex'])->name('crops.index');
    Route::get('/crops/create', [CropRuleController::class, 'cropsCreate'])->name('crops.create');
    Route::post('/crops', [CropRuleController::class, 'cropsStore'])->name('crops.store');
    Route::get('/crops/{crop}/edit', [CropRuleController::class, 'cropsEdit'])->name('crops.edit');
    Route::put('/crops/{crop}', [CropRuleController::class, 'cropsUpdate'])->name('crops.update');
    Route::delete('/crops/{crop}', [CropRuleController::class, 'cropsDestroy'])->name('crops.destroy');

    // Rule management
    Route::get('/rules', [CropRuleController::class, 'rulesIndex'])->name('rules.index');
    Route::get('/rules/create', [CropRuleController::class, 'rulesCreate'])->name('rules.create');
    Route::post('/rules', [CropRuleController::class, 'rulesStore'])->name('rules.store');
    Route::get('/rules/{rule}/edit', [CropRuleController::class, 'rulesEdit'])->name('rules.edit');
    Route::put('/rules/{rule}', [CropRuleController::class, 'rulesUpdate'])->name('rules.update');
    Route::delete('/rules/{rule}', [CropRuleController::class, 'rulesDestroy'])->name('rules.destroy');
});