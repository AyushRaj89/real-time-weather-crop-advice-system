<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crop_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crop_id')->constrained()->onDelete('cascade');
 
            // Temperature conditions (°C)
            $table->decimal('min_temperature', 5, 2)->nullable();
            $table->decimal('max_temperature', 5, 2)->nullable();
 
            // Humidity conditions (%)
            $table->integer('min_humidity')->nullable();
            $table->integer('max_humidity')->nullable();
 
            // Rainfall conditions (mm)
            $table->decimal('min_rainfall', 8, 2)->nullable();
            $table->decimal('max_rainfall', 8, 2)->nullable();
 
            // Weather condition match
            $table->string('weather_condition')->nullable(); // Rain, Clear, Clouds, etc.
 
            // Season match
            $table->string('season')->nullable(); // Summer, Winter, Monsoon, All
 
            // Priority: higher = shown first
            $table->integer('priority')->default(1);
 
            // Alert/advice message for this rule
            $table->text('advice')->nullable();
 
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_rules');
    }
};
