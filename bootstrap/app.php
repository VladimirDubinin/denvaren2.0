<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\TelegramBot\Infrastructure\Middlewares\CheckSecretTokenMiddleware;

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
            \App\TelegramBot\Application\ConsoleCommands\SetWebhook::class,
            \App\TelegramBot\Application\ConsoleCommands\SetMyCommands::class,
            \App\TelegramBot\Application\ConsoleCommands\AIRequest::class,
            \App\TelegramBot\Application\ConsoleCommands\CheckNotifications::class,
            \App\TelegramBot\Application\ConsoleCommands\GenNotification::class,
            \App\TelegramBot\Application\ConsoleCommands\SimpleNotification::class
        ]
    )
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
