<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->string('prefix')->default('Film')->after('title');
            $table->string('slug')->nullable()->after('prefix');
        });

        Schema::table('series', function (Blueprint $table) {
            $table->string('prefix')->default('Serial')->after('title');
            $table->string('slug')->nullable()->after('prefix');
        });
    }

    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn(['prefix', 'slug']);
        });

        Schema::table('series', function (Blueprint $table) {
            $table->dropColumn(['prefix', 'slug']);
        });
    }
};
