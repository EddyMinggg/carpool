<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
// Import your AdminMiddleware (ensure this line exists)
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\Localization;
use App\Http\Middleware\PreventDriverTripAccess;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__ . '/../routes/web.php',
            __DIR__ . '/../routes/admin.php',
            __DIR__ . '/../routes/super-admin.php',
        ],
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware
            ->alias([
                'admin' => AdminMiddleware::class,
                'super_admin' => SuperAdminMiddleware::class,
                'prevent.driver.trips' => PreventDriverTripAccess::class,
            ])
            ->web(append: [
                Localization::class
            ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
