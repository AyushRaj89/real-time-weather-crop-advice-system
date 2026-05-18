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
        Schema::create('crops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('local_name')->nullable();
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('growing_season')->nullable(); // e.g. "Kharif", "Rabi", "All"
            $table->integer('growth_days')->nullable();   // days to harvest
            $table->string('water_requirement')->nullable(); // Low / Medium / High
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crops');
    }
};
