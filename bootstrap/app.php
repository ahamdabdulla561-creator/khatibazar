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
        $middleware->trustProxies(at: '*');

        $middleware->validateCsrfTokens(except: [
            'admin/login',
            'admin/login/*',
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'customer' => \App\Http\Middleware\IsCustomer::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            return redirect()->back()->withInput()->with('error', 'আপনার পেজের সেশনের মেয়াদ শেষ হয়েছিল। অনুগ্রহ করে আবার চেষ্টা করুন।');
        });
    })->create();
