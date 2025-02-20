<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Movie;
use App\Models\Serie;
use App\Models\Genre;

class FetchTmdbData extends Command
{
    protected $signature = 'tmdb:fetch';
    protected $description = 'Pobiera dane z TMDB i zapisuje je w bazie danych';

    // Języki dla filmów i seriali
    protected $languages = ['en-US', 'pl-PL', 'de-DE'];
    // Języki dla gatunków (używamy krótszych kodów)
    protected $genreLanguages = ['en', 'pl', 'de'];

    public function handle()
    {
        $apiKey = config('services.tmdb.api_key');
        $readToken = config('services.tmdb.read_token');

        $this->fetchMovies($apiKey, $readToken);
        $this->fetchSeries($apiKey, $readToken);
        $this->fetchGenres($apiKey, $readToken);

        $this->info('Dane z TMDB zostały pobrane i zapisane.');
    }

    protected function fetchMovies($apiKey, $readToken)
    {
        $this->info('Pobieranie filmów...');
        $response = Http::withToken($readToken)
            ->get('https://api.themoviedb.org/3/movie/popular', [
                'api_key'  => $apiKey,
                'language' => 'en-US',
                'page'     => 1,
            ]);

        if (!$response->successful()) {
            $this->error('Błąd podczas pobierania filmów.');
            return;
        }

        $movies = array_slice($response->json()['results'], 0, 50);
        foreach ($movies as $data) {
            // Utworzenie rekordu z domyślnymi tłumaczeniami (puste ciągi)
            $movie = Movie::updateOrCreate(
                ['tmdb_id' => $data['id']],
                [
                    'title'        => ['en-US' => '', 'pl-PL' => '', 'de-DE' => ''],
                    'overview'     => ['en-US' => '', 'pl-PL' => '', 'de-DE' => ''],
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

            // Uzupełniamy tłumaczenia dla każdego języka
            foreach ($this->languages as $lang) {
                $detail = Http::withToken($readToken)
                    ->get("https://api.themoviedb.org/3/movie/{$data['id']}", [
                        'api_key'  => $apiKey,
                        'language' => $lang,
                    ])->json();

                $movie->setTranslation('title', $lang, $detail['title'] ?? '');
                $movie->setTranslation('overview', $lang, $detail['overview'] ?? '');
            }
            $movie->save();
        }
        $this->info('Filmy pobrane i zapisane.');
    }

    protected function fetchSeries($apiKey, $readToken)
    {
        $this->info('Pobieranie seriali...');
        $response = Http::withToken($readToken)
            ->get('https://api.themoviedb.org/3/tv/popular', [
                'api_key'  => $apiKey,
                'language' => 'en-US',
                'page'     => 1,
            ]);

        if (!$response->successful()) {
            $this->error('Błąd podczas pobierania seriali.');
            return;
        }

        $series = array_slice($response->json()['results'], 0, 10);
        foreach ($series as $data) {
            $serie = Serie::updateOrCreate(
                ['tmdb_id' => $data['id']],
                [
                    'title'         => ['en-US' => '', 'pl-PL' => '', 'de-DE' => ''],
                    'overview'      => ['en-US' => '', 'pl-PL' => '', 'de-DE' => ''],
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
                $detail = Http::withToken($readToken)
                    ->get("https://api.themoviedb.org/3/tv/{$data['id']}", [
                        'api_key'  => $apiKey,
                        'language' => $lang,
                    ])->json();

                $serie->setTranslation('title', $lang, $detail['name'] ?? '');
                $serie->setTranslation('overview', $lang, $detail['overview'] ?? '');
            }
            $serie->save();
        }
        $this->info('Seriale pobrane i zapisane.');
    }

    protected function fetchGenres($apiKey, $readToken)
    {
        $this->info('Pobieranie gatunków...');
        $allGenres = [];
        // Pobieramy gatunki dla każdego języka
        foreach ($this->genreLanguages as $lang) {
            $response = Http::withToken($readToken)
                ->get('https://api.themoviedb.org/3/genre/movie/list', [
                    'api_key'  => $apiKey,
                    'language' => $lang,
                ]);

            if (!$response->successful()) {
                $this->error("Błąd podczas pobierania gatunków dla języka: $lang.");
                continue;
            }

            foreach ($response->json()['genres'] as $data) {
                $allGenres[$data['id']][$lang] = $data['name'];
                $allGenres[$data['id']]['tmdb_id'] = $data['id'];
            }
        }

        foreach ($allGenres as $genreData) {
            $translations = [
                'en-EN' => $genreData['en'] ?? '',
                'pl-PL' => $genreData['pl'] ?? '',
                'de-DE' => $genreData['de'] ?? '',
            ];
            Genre::updateOrCreate(
                ['tmdb_id' => $genreData['tmdb_id']],
                ['name' => $translations]
            );
        }

        $this->info('Gatunki pobrane i zapisane.');
    }
}
