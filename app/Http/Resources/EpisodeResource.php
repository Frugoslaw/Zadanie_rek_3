<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EpisodeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = $request->query('lang', 'en');

        return [
            'id'             => $this->id,
            'tmdb_id'        => $this->tmdb_id,
            'episode_number' => $this->episode_number,
            'name'           => $this->resource->getTranslation('name', $lang),
            'overview'       => $this->resource->getTranslation('overview', $lang),
            'air_date'       => $this->air_date,
            'runtime'        => $this->runtime,
            'still_path'     => $this->still_path,
            'vote_average'   => $this->vote_average,
        ];
    }
}
