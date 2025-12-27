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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth'  => \Illuminate\Auth\Middleware\Authenticate::class,
            'guest' => \Illuminate\Auth\Middleware\RedirectIfAuthenticated::class,
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'branch.selected' => \App\Http\Middleware\CheckBranchSelected::class,
            'clear.branch' => \App\Http\Middleware\ClearBranchOnLogout::class,
        ]);
        $middleware->validateCsrfTokens(except: [
        'payment/callback',
        '/payment/callback'
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();