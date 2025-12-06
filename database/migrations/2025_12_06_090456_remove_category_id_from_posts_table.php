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
        Schema::table('posts', function (Blueprint $table) {
            // Bước 1: Drop foreign key constraint trước
            // Laravel naming convention: [table]_[column]_foreign
            $table->dropForeign(['category_id']);
            
            // Bước 2: Drop column sau khi đã drop foreign key
            $table->dropColumn('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Khôi phục lại column và foreign key nếu rollback
            $table->foreignId('category_id')->nullable()->after('user_id');
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('set null');
        });
    }
};
