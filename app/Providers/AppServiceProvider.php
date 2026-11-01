<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\Memory;
use App\Models\User;
use App\Policies\EventPolicy;
use App\Policies\MemoryPolicy;
use Illuminate\Support\Facades\Gate;
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
        // Register Model Policies
        Gate::policy(Memory::class, MemoryPolicy::class);
        Gate::policy(Event::class, EventPolicy::class);

        // Define Application Authorization Gates
        Gate::define('create-memories', fn(User $user) => $user->canCreateMemory());
        Gate::define('create-events', fn(User $user) => $user->canCreateEvent());
        Gate::define('manage-tributes', fn(User $user) => $user->isMemoryUser() || $user->isSuperAdmin() || (method_exists($user, 'hasPermissionTo') && $user->hasPermissionTo('manage-tributes')));

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
