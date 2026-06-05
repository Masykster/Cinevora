<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use App\Events\CafeOrderStatusChanged;
use App\Listeners\SendCafeOrderNotification;
use App\Models\Cinema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            CafeOrderStatusChanged::class,
            SendCafeOrderNotification::class,
        );

        // Force HTTPS in production
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }

        // Share available cities for the navbar city selector
        View::composer('layouts.app', function ($view) {
            $view->with('navCities', Cinema::distinct()->orderBy('city')->pluck('city'));
        });
    }
}
