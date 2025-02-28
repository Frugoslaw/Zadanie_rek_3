<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tmdb_id')->unique();
            $table->foreignId('season_id')->constrained()->onDelete('cascade');
            $table->integer('episode_number');
            $table->string('name')->nullable();
            $table->text('overview')->nullable();
            $table->date('air_date')->nullable();
            $table->integer('runtime')->nullable();
            $table->string('still_path')->nullable();
            $table->float('vote_average')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
