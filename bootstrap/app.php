<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\LanguageMiddleware;
use App\Http\Middleware\UserIdMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            '*'
        ]);
        $middleware->append(LanguageMiddleware::class);
        $middleware->append(UserIdMiddleware::class);
        $middleware->alias([
            'auth.admin' => AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
