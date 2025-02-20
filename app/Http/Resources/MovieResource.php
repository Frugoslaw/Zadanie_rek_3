<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $lang = $request->query('lang', 'en-US');
        return [
            'tmdb_id'      => $this->tmdb_id,
            'title'        => $this->getTranslation('title', $lang),
            'overview'     => $this->getTranslation('overview', $lang),
            'release_date' => $this->release_date,
            'poster_path'  => $this->poster_path,
            'backdrop_path' => $this->backdrop_path,
            'vote_average' => $this->vote_average,
            'vote_count'   => $this->vote_count,
            'popularity'   => $this->popularity,
            'adult'        => $this->adult,
            'video'        => $this->video,
        ];
    }
}
