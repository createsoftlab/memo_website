<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;

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
        View::composer('*', function ($view) {
            if (!array_key_exists('user', $view->getData())) {
                $view->with('user', Auth::user());
            }
            $view->with('authUser', Auth::user());
        });

        $visitorIp = '8.8.1.1';
        $position = Location::get($visitorIp);

        View::share('visitorIp', $visitorIp);

        View::share(
            'visitorCountry',
            is_object($position) ? $position->countryName : null
        );

        // View::share(
        //     'visitorState',
        //     is_object($position) ? $position->regionName : null
        // );
    }
}
