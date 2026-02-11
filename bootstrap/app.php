<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Scheduler; // ✅ This line is missing


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withSchedule(function (Illuminate\Console\Scheduling\Schedule $schedule) {
        $schedule->command('fetch:learners')->dailyAt('2:00');
        $schedule->command('yhub:sync-learners')->dailyAt('02:30');
        $schedule->command('backup:mysql')->dailyAt('03:00');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
