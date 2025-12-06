<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $posts = \App\Models\Post::whereNotNull('category_id')->get();
        
        foreach ($posts as $post) {
            if ($post->category_id) {
                $post->categories()->attach($post->category_id, ['is_primary' => true]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('many', function (Blueprint $table) {
            //
        });
    }
};
