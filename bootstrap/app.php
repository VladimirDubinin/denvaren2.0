<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {
        // Ежедневное отслеживание дат и отправка уведомлений
        $schedule->command('notifications:check')->dailyAt('09:00');
    })
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withCommands(
        [
            \App\TelegramBot\Application\Commands\AIRequest::class,
            \App\TelegramBot\Application\Commands\SetWebhook::class,
            \App\TelegramBot\Application\Commands\CheckNotifications::class,
            \App\TelegramBot\Application\Commands\GenNotification::class,
            \App\TelegramBot\Application\Commands\SimpleNotification::class
        ]
    )
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
