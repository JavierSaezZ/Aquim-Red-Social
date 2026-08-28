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
     Schema::create('followers', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('follower_id'); // El usuario que sigue
    $table->unsignedBigInteger('followed_id'); // El usuario que es seguido
    $table->timestamps();

    // Claves foráneas referenciando a la tabla users
    $table->foreign('follower_id')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('followed_id')->references('id')->on('users')->onDelete('cascade');

    // Evitar que un usuario siga a la misma persona dos veces
    $table->unique(['follower_id', 'followed_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('followers');
    }
};
