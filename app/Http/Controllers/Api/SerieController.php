<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Serie;
use Illuminate\Http\Request;
use App\Http\Resources\SerieResource;

class SerieController extends Controller
{
    public function index(Request $request)
    {
        $series = Serie::all();
        return response()->json(SerieResource::collection($series), 200);
    }

    public function show(Request $request, $tmdbId)
    {
        $serie = Serie::where('tmdb_id', $tmdbId)->first();
        if (!$serie) {
            return response()->json(['error' => 'Serial nie został znaleziony.'], 404);
        }
        return response()->json(new SerieResource($serie), 200);
    }
}
