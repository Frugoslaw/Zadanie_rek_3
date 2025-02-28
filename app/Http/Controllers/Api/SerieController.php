<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Serie;
use Illuminate\Http\Request;
use App\Http\Resources\SerieResource;

class SerieController extends Controller
{
    public function index()
    {
        $series = Serie::all();
        return response()->json(SerieResource::collection($series), 200);
    }

    public function show($id)
    {
        $serie = Serie::findOrFail($id);
        return response()->json(new SerieResource($serie), 200);
    }

    public function updatePrefix(UpdatePrefixRequest $request, $id)
    {
        $serie = Serie::findOrFail($id);
        $serie->prefix = $request->validated();
        $serie->save();

        return response()->json([
            'message' => 'Prefix updated successfully',
            'serie'   => new SerieResource($serie)
        ], 200);
    }
}
