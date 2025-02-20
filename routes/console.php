<?php

use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\FetchTmdbData;

Artisan::command('tmdb:fetch', function () {
    $command = new FetchTmdbData();
    $command->setLaravel(app());
    $command->setOutput($this->getOutput());
    $command->handle();
})->purpose('Pobiera dane z TMDB i zapisuje je w bazie danych');
