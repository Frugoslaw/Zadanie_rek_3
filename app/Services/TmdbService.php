<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\RequestException;
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

    public function fetchMovies(): bool
    {
        try {
            Log::info('Fetching movies...');

            $response = Http::withToken($this->readToken)
                ->get('https://api.themoviedb.org/3/movie/popular', [
                    'api_key'  => $this->apiKey,
                    'language' => LanguageEnum::ENGLISH->value,
                    'page'     => 1,
                ]);

            if (!$response->successful()) {
                throw new RequestException($response);
            }

            $movies = array_slice($response->json('results', []), 0, 50);

            if (empty($movies)) {
                Log::warning('API returned empty movie list!');
                return false;
            }

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

                // Pobieranie i przypisywanie gatunków do filmu
                if (!empty($data['genre_ids'])) {
                    $genres = Genre::whereIn('tmdb_id', $data['genre_ids'])->pluck('id');
                    $movie->genres()->sync($genres);
                }
                Log::debug("Movie ID: {$data['id']}, Genres: " . json_encode($data['genre_ids']));


                // Pobieranie tłumaczeń
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

            Log::info('Movies fetched successfully.');
            return true;
        } catch (RequestException $e) {
            Log::error('TMDB Scraper - fetchMovies failed: ' . $e->getMessage());
            return false;
        }
    }

    public function fetchSeries(): bool
    {
        try {
            Log::info('Fetching series...');

            $response = Http::withToken($this->readToken)
                ->get('https://api.themoviedb.org/3/tv/popular', [
                    'api_key'  => $this->apiKey,
                    'language' => LanguageEnum::ENGLISH->value,
                    'page'     => 1,
                ]);

            if (!$response->successful()) {
                throw new RequestException($response);
            }

            $series = array_slice($response->json('results', []), 0, 10);

            if (empty($series)) {
                Log::warning('API returned empty series list!');
                return false;
            }

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

                // Pobieranie i przypisywanie gatunków do serialu
                if (!empty($data['genre_ids'])) {
                    $genres = Genre::whereIn('tmdb_id', $data['genre_ids'])->pluck('id');
                    $serie->genres()->sync($genres);
                }

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

            Log::info('Series fetched successfully.');
            return true;
        } catch (RequestException $e) {
            Log::error('TMDB Scraper - fetchSeries failed: ' . $e->getMessage());
            return false;
        }
    }

    public function fetchGenres(): bool
    {
        try {
            Log::info('Fetching genres...');
            $allGenres = [];

            $response = Http::withToken($this->readToken)
                ->get('https://api.themoviedb.org/3/genre/movie/list', [
                    'api_key'  => $this->apiKey,
                    'language' => LanguageEnum::ENGLISH->value,
                ]);

            if (!$response->successful()) {
                throw new RequestException($response);
            }

            foreach ($response->json('genres', []) as $data) {
                $allGenres[$data['id']] = [
                    'tmdb_id' => $data['id'],
                    'name'    => [],
                ];
            }

            foreach ($this->languages as $lang) {
                $response = Http::withToken($this->readToken)
                    ->get('https://api.themoviedb.org/3/genre/movie/list', [
                        'api_key'  => $this->apiKey,
                        'language' => $lang,
                    ])->json();

                foreach ($response['genres'] as $data) {
                    $allGenres[$data['id']]['name'][$lang] = $data['name'];
                }
            }

            foreach ($allGenres as $genreData) {
                Genre::updateOrCreate(
                    ['tmdb_id' => $genreData['tmdb_id']],
                    ['name' => $genreData['name']]
                );
            }

            Log::info('Genres fetched successfully.');
            return true;
        } catch (RequestException $e) {
            Log::error('TMDB Scraper - fetchGenres failed: ' . $e->getMessage());
            return false;
        }
    }
}
