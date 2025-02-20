<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GenreResource extends JsonResource
{
    public function toArray($request)
    {
        $lang = $request->query('lang', 'en');
        return [
            'tmdb_id' => $this->tmdb_id,
            'name'    => $this->getTranslation('name', $lang),
        ];
    }
}
