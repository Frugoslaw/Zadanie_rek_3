<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tmdb_id')->unique();
            $table->json('title'); // Przechowujemy "name" jako "title"
            $table->json('overview')->nullable();
            $table->date('first_air_date')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->float('vote_average')->default(0);
            $table->unsignedInteger('vote_count')->default(0);
            $table->float('popularity')->default(0);
            $table->json('origin_country')->nullable(); // Przechowujemy jako JSON
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('series');
    }
};
