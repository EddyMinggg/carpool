<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
// Import your AdminMiddleware (ensure this line exists)
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\Localization;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware
            ->alias([
                'admin' => AdminMiddleware::class,
            ])
            ->web(append: [
                Localization::class
            ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
