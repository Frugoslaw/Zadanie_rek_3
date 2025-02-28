<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Serie extends Model
{
    use HasTranslations;

    protected $fillable = [
        'tmdb_id',
        'title',
        'overview',
        'first_air_date',
        'poster_path',
        'backdrop_path',
        'vote_average',
        'vote_count',
        'popularity',
        'origin_country'
    ];

    public $translatable = ['title', 'overview'];

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'genre_serie');
    }

    public function seasons()
    {
        return $this->hasMany(Season::class);
    }
}
