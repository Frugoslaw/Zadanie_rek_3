<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeasonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = $request->query('lang', 'en');

        return [
            'id'            => $this->id,
            'tmdb_id'       => $this->tmdb_id,
            'season_number' => $this->season_number,
            'name'          => $this->resource->getTranslation('name', $lang),
            'overview'      => $this->resource->getTranslation('overview', $lang),
            'air_date'      => $this->air_date,
            'poster_path'   => $this->poster_path,
            'vote_average'  => $this->vote_average,
            'episodes'      => EpisodeResource::collection($this->episodes),
        ];
    }
}
