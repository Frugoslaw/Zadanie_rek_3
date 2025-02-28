<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Movie extends Model
{
    use HasTranslations;

    protected $fillable = [
        'tmdb_id',
        'title',
        'overview',
        'release_date',
        'poster_path',
        'backdrop_path',
        'vote_average',
        'vote_count',
        'popularity',
        'adult',
        'video'
    ];

    public $translatable = ['title', 'overview'];


    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'genre_movie');
    }
}
