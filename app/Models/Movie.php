<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Enums\LanguageEnum;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
        'prefix',
        'slug'
    ];

    public $translatable = ['title', 'overview', 'slug'];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_movie');
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($movie) {
            $movie->generateMultilingualSlugs();
        });
    }

    public function generateMultilingualSlugs(): void
    {
        $slugs = [];

        foreach (LanguageEnum::cases() as $lang) {
            $title = $this->getTranslation('title', $lang->value);
            if (!$title) continue;

            $baseSlug = Str::slug($title);
            $uniqueSlug = $this->ensureUniqueSlug($baseSlug, $lang->value);

            $slugs[$lang->value] = $uniqueSlug;
        }

        if (!empty($slugs)) {
            $this->setTranslations('slug', $slugs);
        }
    }

    private function ensureUniqueSlug(string $slug, string $lang): string
    {
        $count = 0;
        $originalSlug = $slug;

        while (DB::table($this->getTable())->where("slug->{$lang}", $slug)->exists()) {
            $count++;
            $slug = "{$originalSlug}-{$count}";
        }

        return $slug;
    }
}
