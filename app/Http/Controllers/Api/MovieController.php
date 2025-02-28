<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Http\Requests\UpdatePrefixRequest;
use App\Http\Resources\MovieResource;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::all();
        return response()->json(MovieResource::collection($movies), 200);
    }

    public function show($id)
    {
        $movie = Movie::findOrFail($id);
        return response()->json(new MovieResource($movie), 200);
    }

    public function updatePrefix(UpdatePrefixRequest $request, $id)
    {
        $movie = Movie::findOrFail($id);
        $movie->prefix = $request->validated();
        $movie->save();

        return response()->json([
            'message' => 'Prefix updated successfully',
            'movie'   => new MovieResource($movie)
        ], 200);
    }
}
