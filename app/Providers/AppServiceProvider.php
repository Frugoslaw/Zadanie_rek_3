<?php

namespace App\Providers;

use App\Services\TmdbService;
use App\Console\Commands\FetchTmdbData;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
