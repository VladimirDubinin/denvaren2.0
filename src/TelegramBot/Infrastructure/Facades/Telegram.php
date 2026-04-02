<?php

namespace App\TelegramBot\Infrastructure\Facades;

use Illuminate\Support\Facades\Facade;
use App\TelegramBot\Application\Services\TelegramService;

/**
 * @method static string report(string $message)
 */
class Telegram extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return TelegramService::class;
    }
}
