<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Route::singularResourceParameters(false);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
