<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [ // Untuk grup middleware 'web'
            \App\Http\Middleware\MarkNotificationAsRead::class, //
        ]);
        
        $middleware->alias([ // Untuk alias middleware
            'role' => \App\Http\Middleware\CheckRole::class, //
            'member' => \App\Http\Middleware\CheckIsMember::class, //
        ]);

        // Middleware global yang Anda miliki dari file lama, tambahkan di sini jika tidak ada secara default
        // Contoh:
        // $middleware->append([
        //     \App\Http\Middleware\TrustProxies::class,
        //     \Illuminate\Http\Middleware\HandleCors::class,
        //     \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        //     \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        //     \App\Http\Middleware\TrimStrings::class,
        //     \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        // ]);
        // Anda juga bisa memeriksa `app/Http/Kernel.php` dari file lama untuk melihat middleware yang digunakan di sana
        // dan memindahkannya sesuai kebutuhan ke `bootstrap/app.php` (global) atau `app/Http/Kernel.php` (grup/alias).

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();