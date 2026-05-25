<?php
// File: database/migrations/2026_05_23_000003_create_irrigation_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('irrigation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('crop_plan_id')->nullable()->constrained()->onDelete('set null');

            $table->string('field_name');                     // field / khet label
            $table->enum('method', ['drip', 'sprinkler', 'flood', 'furrow', 'manual'])
                  ->default('manual');
            $table->decimal('water_used_liters', 10, 2);      // litres applied
            $table->integer('duration_minutes');               // how long
            $table->date('irrigated_on');                      // date of irrigation
            $table->boolean('next_reminder_set')->default(false);
            $table->date('next_irrigation_date')->nullable();  // when to irrigate again
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'irrigated_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('irrigation_logs');
    }
};