<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\SerieController;
use App\Http\Controllers\Api\GenreController;

Route::prefix('v1')->group(function () {
    Route::get('movies', [MovieController::class, 'index']);
    Route::get('movies/{tmdbId}', [MovieController::class, 'show']);

    Route::get('series', [SerieController::class, 'index']);
    Route::get('series/{tmdbId}', [SerieController::class, 'show']);

    Route::get('genres', [GenreController::class, 'index']);
    Route::get('genres/{tmdbId}', [GenreController::class, 'show']);
});
