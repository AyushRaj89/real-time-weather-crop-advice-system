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
        Schema::create('weather_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('city');
            $table->string('country')->nullable();
            $table->decimal('temperature', 5, 2);       // °C
            $table->decimal('feels_like', 5, 2)->nullable();
            $table->integer('humidity');                 // %
            $table->decimal('rainfall', 8, 2)->default(0); // mm (last 1h)
            $table->decimal('wind_speed', 6, 2)->nullable(); // m/s
            $table->string('weather_main');              // e.g. Rain, Clear
            $table->string('weather_description')->nullable();
            $table->string('weather_icon')->nullable();
            $table->integer('pressure')->nullable();     // hPa
            $table->integer('visibility')->nullable();   // meters
            $table->timestamp('fetched_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_logs');
    }
};
