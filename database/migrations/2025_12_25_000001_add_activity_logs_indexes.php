<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // Index for log type filtering
            $table->index('log_name');

            // Index for date range filtering and sorting
            $table->index('created_at');

            // Note: subject_type + subject_id index already exists from nullableMorphs
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['log_name']);
            $table->dropIndex(['created_at']);
        });
    }
};
