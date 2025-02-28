<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class MovieResource extends JsonResource
{
    public function toArray($request)
    {
        $lang = $request->query('lang', 'en-US');
        $title = $this->getTranslation('title', $lang);

        return [
            'id'            => $this->id,
            'tmdb_id'       => $this->tmdb_id,
            'title'         => $title,
            'overview'      => $this->getTranslation('overview', $lang),
            'seo_title'     => $title . ' ' . $this->prefix, // SEO tytuł
            'slug'          => Str::slug($title), // Dynamiczne generowanie sluga
            'release_date'  => $this->release_date,
            'poster_path'   => $this->poster_path,
            'backdrop_path' => $this->backdrop_path,
            'vote_average'  => $this->vote_average,
            'vote_count'    => $this->vote_count,
            'popularity'    => $this->popularity,
            'adult'         => $this->adult,
            'video'         => $this->video,
        ];
    }
}
