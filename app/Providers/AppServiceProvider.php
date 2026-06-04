<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
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

        // Share available cities for the navbar city selector
        View::composer('layouts.app', function ($view) {
            $view->with('navCities', Cinema::distinct()->orderBy('city')->pluck('city'));
        });
    }
}
