<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;
use App\Strava;

class StravaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        App::singleton(Strava::class, function(){
            return new Strava(config('services.strava.key'), config('services.strava.secret'));
        });
    }
}
