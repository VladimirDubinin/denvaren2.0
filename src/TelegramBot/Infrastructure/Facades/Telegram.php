<?php

namespace Src\TelegramBot\Infrastructure\Facades;

use Illuminate\Support\Facades\Facade;
use Src\TelegramBot\Application\Services\TelegramService;

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
