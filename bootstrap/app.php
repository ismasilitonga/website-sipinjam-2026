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
        // Alias middleware 'role' untuk dipakai di routes
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Override 'guest' middleware agar redirect ke landing page
        $middleware->redirectGuestsTo(fn () => route('landingpage'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
