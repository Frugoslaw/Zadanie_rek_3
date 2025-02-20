<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use App\Http\Resources\MovieResource;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $movies = Movie::all();
        // Zwracamy kolekcję przekształconą przez MovieResource wraz z kodem HTTP 200
        return response()->json(MovieResource::collection($movies), 200);
    }

    public function show(Request $request, $tmdbId)
    {
        $movie = Movie::where('tmdb_id', $tmdbId)->first();
        if (!$movie) {
            return response()->json(['error' => 'Film nie został znaleziony.'], 404);
        }
        return response()->json(new MovieResource($movie), 200);
    }
}
