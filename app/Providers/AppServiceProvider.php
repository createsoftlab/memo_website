<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

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

        View::share('visitorIp', request()->ip());
    }
}
