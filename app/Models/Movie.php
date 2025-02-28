<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Str;

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
        'video',
        'prefix'
    ];

    public $translatable = ['title', 'overview'];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_movie');
    }
}
