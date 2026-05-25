<?php
// File: database/migrations/2026_05_23_000002_create_crop_plans_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crop_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('crop_id')->constrained()->onDelete('cascade');

            $table->string('field_name');                    // e.g. "North Field"
            $table->decimal('area_acres', 8, 2);             // field size
            $table->date('planned_sow_date');                // when farmer wants to sow
            $table->date('expected_harvest_date')->nullable();// auto-computed from crop growth_days
            $table->enum('status', ['planned', 'sowing', 'growing', 'harvested', 'failed'])
                  ->default('planned');
            $table->text('notes')->nullable();               // farmer notes
            $table->string('city')->nullable();              // city for weather context
            $table->timestamps();

            $table->index(['user_id', 'planned_sow_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crop_plans');
    }
};