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
        Schema::create('movies_catalog_favorites', function (Blueprint $table) {
            $table->id();
            $table->integer('id_tmdb')->unique();
            $table->string('movie_title');
            $table->text('overview')->nullable();
            $table->string('poster_path')->nullable();
            $table->date('release_date')->nullable();
            $table->float('rating', 3, 1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies_catalog_favorites');
    }
};
