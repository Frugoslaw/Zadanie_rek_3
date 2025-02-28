<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use App\Enums\LanguageEnum;

class MovieResource extends JsonResource
{
    public function toArray($request)
    {
        $lang = $request->query('lang', 'en-US');

        return [
            'id'            => $this->id,
            'tmdb_id'       => $this->tmdb_id,
            'title'         => $this->getTranslation('title', $lang),
            'overview'      => $this->getTranslation('overview', $lang),
            'seo_title'     => $this->getTranslation('title', $lang) . ' ' . $this->prefix,
            'slug'          => $this->getTranslation('slug', $lang),
            'release_date'  => $this->release_date,
            'poster_path'   => $this->poster_path,
            'backdrop_path' => $this->backdrop_path,
            'vote_average'  => $this->vote_average,
            'vote_count'    => $this->vote_count,
            'popularity'    => $this->popularity,
            'adult'         => $this->adult,
            'video'         => $this->video,
            'prefix'        => $this->prefix,
        ];
    }
}
