<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Movie;
use App\Models\Serie;
use App\Models\Genre;
use App\Enums\LanguageEnum;

class TmdbService
{
    protected string $apiKey;
    protected string $readToken;
    protected array $languages;

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key');
        $this->readToken = config('services.tmdb.read_token');
        $this->languages = LanguageEnum::all();
    }

    public function fetchMovies(): void
    {
        try {
            $response = Http::withToken($this->readToken)
                ->get('https://api.themoviedb.org/3/movie/popular', [
                    'api_key'  => $this->apiKey,
                    'language' => LanguageEnum::ENGLISH->value,
                    'page'     => 1,
                ]);

            if (!$response->successful()) {
                throw new \Exception('Error fetching movies from TMDB.');
            }

            $movies = array_slice($response->json('results', []), 0, 50);
            foreach ($movies as $data) {
                $movie = Movie::updateOrCreate(
                    ['tmdb_id' => $data['id']],
                    [
                        'title'        => [],
                        'overview'     => [],
                        'release_date' => $data['release_date'] ?? null,
                        'poster_path'  => $data['poster_path'] ?? null,
                        'backdrop_path' => $data['backdrop_path'] ?? null,
                        'vote_average' => $data['vote_average'] ?? 0,
                        'vote_count'   => $data['vote_count'] ?? 0,
                        'popularity'   => $data['popularity'] ?? 0,
                        'adult'        => $data['adult'] ?? false,
                        'video'        => $data['video'] ?? false,
                    ]
                );

                foreach ($this->languages as $lang) {
                    $detail = Http::withToken($this->readToken)
                        ->get("https://api.themoviedb.org/3/movie/{$data['id']}", [
                            'api_key'  => $this->apiKey,
                            'language' => $lang,
                        ])->json();

                    $movie->setTranslation('title', $lang, $detail['title'] ?? '');
                    $movie->setTranslation('overview', $lang, $detail['overview'] ?? '');
                }
                $movie->save();
            }
        } catch (\Exception $e) {
            Log::error('TMDB Scraper - fetchMovies failed: ' . $e->getMessage());
        }
    }

    public function fetchSeries(): void
    {
        try {
            $response = Http::withToken($this->readToken)
                ->get('https://api.themoviedb.org/3/tv/popular', [
                    'api_key'  => $this->apiKey,
                    'language' => LanguageEnum::ENGLISH->value,
                    'page'     => 1,
                ]);

            if (!$response->successful()) {
                throw new \Exception('Error fetching series from TMDB.');
            }

            $series = array_slice($response->json('results', []), 0, 10);
            foreach ($series as $data) {
                $serie = Serie::updateOrCreate(
                    ['tmdb_id' => $data['id']],
                    [
                        'title'         => [],
                        'overview'      => [],
                        'first_air_date' => $data['first_air_date'] ?? null,
                        'poster_path'   => $data['poster_path'] ?? null,
                        'backdrop_path' => $data['backdrop_path'] ?? null,
                        'vote_average'  => $data['vote_average'] ?? 0,
                        'vote_count'    => $data['vote_count'] ?? 0,
                        'popularity'    => $data['popularity'] ?? 0,
                        'origin_country' => json_encode($data['origin_country'] ?? []),
                    ]
                );

                foreach ($this->languages as $lang) {
                    $detail = Http::withToken($this->readToken)
                        ->get("https://api.themoviedb.org/3/tv/{$data['id']}", [
                            'api_key'  => $this->apiKey,
                            'language' => $lang,
                        ])->json();

                    $serie->setTranslation('title', $lang, $detail['name'] ?? '');
                    $serie->setTranslation('overview', $lang, $detail['overview'] ?? '');
                }
                $serie->save();
            }
        } catch (\Exception $e) {
            Log::error('TMDB Scraper - fetchSeries failed: ' . $e->getMessage());
        }
    }

    public function fetchGenres(): void
    {
        try {
            $allGenres = [];

            foreach ($this->languages as $lang) {
                $response = Http::withToken($this->readToken)
                    ->get('https://api.themoviedb.org/3/genre/movie/list', [
                        'api_key'  => $this->apiKey,
                        'language' => $lang,
                    ]);

                if (!$response->successful()) {
                    throw new \Exception("Error fetching genres for language: $lang.");
                }

                foreach ($response->json('genres', []) as $data) {
                    $allGenres[$data['id']]['tmdb_id'] = $data['id'];
                    $allGenres[$data['id']][$lang] = $data['name'];
                }
            }

            foreach ($allGenres as $genreData) {
                $translations = [];
                foreach (LanguageEnum::cases() as $langCase) {
                    $translations[$langCase->value] = $genreData[$langCase->value] ?? '';
                }

                Genre::updateOrCreate(
                    ['tmdb_id' => $genreData['tmdb_id']],
                    ['name' => $translations]
                );
            }
        } catch (\Exception $e) {
            Log::error('TMDB Scraper - fetchGenres failed: ' . $e->getMessage());
        }
    }
}
