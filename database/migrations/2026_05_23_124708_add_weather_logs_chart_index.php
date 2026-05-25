<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add composite index on (user_id, fetched_at) so the weather history
     * chart query — ORDER BY fetched_at DESC, filtered by user_id — stays fast
     * even when a farmer has thousands of log rows.
     */
    public function up(): void
    {
        Schema::table('weather_logs', function (Blueprint $table) {
            $table->index(['user_id', 'fetched_at'], 'weather_logs_user_fetched_idx');
        });
    }

    public function down(): void
    {
        Schema::table('weather_logs', function (Blueprint $table) {
            $table->dropIndex('weather_logs_user_fetched_idx');
        });
    }
};