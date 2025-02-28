<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TmdbService;

class FetchTmdbData extends Command
{
    protected $signature = 'tmdb:fetch';
    protected $description = 'Fetches data from TMDB and saves it to the database';

    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
        parent::__construct();
    }

    public function handle(): void
    {
        try {
            $this->info('Fetching movies...');
            $this->tmdbService->fetchMovies();
            $this->info('Movies fetched successfully.');

            $this->info('Fetching series...');
            $this->tmdbService->fetchSeries();
            $this->info('Series fetched successfully.');

            $this->info('Fetching genres...');
            $this->tmdbService->fetchGenres();
            $this->info('Genres fetched successfully.');

            $this->info('TMDB data has been successfully fetched and stored.');
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
