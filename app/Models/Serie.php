<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Str;

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
        'origin_country',
        'prefix'
    ];

    public $translatable = ['title', 'overview'];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_serie');
    }

    public function seasons()
    {
        return $this->hasMany(Season::class);
    }
}
