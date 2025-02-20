<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use App\Http\Resources\GenreResource;

class GenreController extends Controller
{
    public function index(Request $request)
    {
        $genres = Genre::all();
        return response()->json(GenreResource::collection($genres), 200);
    }

    public function show(Request $request, $tmdbId)
    {
        $genre = Genre::where('tmdb_id', $tmdbId)->first();
        if (!$genre) {
            return response()->json(['error' => 'Gatunek nie został znaleziony.'], 404);
        }
        return response()->json(new GenreResource($genre), 200);
    }
}
