<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'module' => \App\Http\Middleware\CheckModulePermission::class,
            'module.permission' => \App\Http\Middleware\CheckModuleAccess::class,
            'can.delete' => \App\Http\Middleware\CheckCanDelete::class,
            'super_admin' => \App\Http\Middleware\EnsureSuperAdmin::class,
            'prevent_admin_creation' => \App\Http\Middleware\PreventAdminContentCreation::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
