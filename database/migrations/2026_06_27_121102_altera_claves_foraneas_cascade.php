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
        Schema::table('likes', function (Blueprint $table) {
            $table->dropForeign('fk_likes_images');

            $table->foreign('image_id', 'fk_likes_images')
                ->references('id')
                ->on('images')
                ->cascadeOnDelete();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign('fk_comments_images');

            $table->foreign('image_id')
                ->references('id')
                ->on('images')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['image_id']);

            $table->foreign('image_id', 'fk_comments_images')
                ->references('id')
                ->on('images');
        });

        Schema::table('likes', function (Blueprint $table) {
            $table->dropForeign('fk_likes_images');

            $table->foreign('image_id', 'fk_likes_images')
                ->references('id')
                ->on('images');
        });
    }
};
