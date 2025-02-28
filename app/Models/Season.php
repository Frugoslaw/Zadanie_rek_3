<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Season extends Model
{
    use HasTranslations;

    protected $fillable = [
        'tmdb_id',
        'serie_id',
        'season_number',
        'name',
        'overview',
        'air_date',
        'poster_path',
        'vote_average'
    ];

    public $translatable = ['name', 'overview'];

    public function serie(): BelongsTo
    {
        return $this->belongsTo(Serie::class);
    }

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }
}
