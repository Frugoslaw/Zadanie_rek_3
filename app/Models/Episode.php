<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Episode extends Model
{
    use HasTranslations;

    protected $fillable = [
        'tmdb_id',
        'season_id',
        'episode_number',
        'name',
        'overview',
        'air_date',
        'runtime',
        'still_path',
        'vote_average'
    ];

    public $translatable = ['name', 'overview'];

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }
}
