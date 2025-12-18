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
        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('url')->nullable()->change();
            $table->string('css_class')->nullable()->after('target');
            $table->string('model_type')->nullable()->after('css_class');
            $table->unsignedBigInteger('model_id')->nullable()->after('model_type');
            $table->index(['model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('url')->nullable(false)->change();
            $table->dropColumn(['css_class', 'model_type', 'model_id']);
        });
    }
};
