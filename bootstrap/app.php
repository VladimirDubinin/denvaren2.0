<?php

use App\TelegramBot\Infrastructure\Middlewares\CheckSecretTokenMiddleware;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

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
        $middleware->api(CheckSecretTokenMiddleware::class);
    })
    ->withCommands(
        [
            \App\TelegramBot\Presentation\Console\SetWebhook::class,
            \App\TelegramBot\Presentation\Console\SetMyCommands::class,
            \App\TelegramBot\Presentation\Console\CheckNotifications::class,
            \App\TelegramBot\Presentation\Console\GenNotification::class,
            \App\TelegramBot\Presentation\Console\SimpleNotification::class
        ]
    )
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
