<?php

namespace App\TelegramBot\Infrastructure\Facades;

use Illuminate\Support\Facades\Facade;
use App\TelegramBot\Application\Services\TelegramService;

/**
 * @method static string sendMessage(string $message, ?int $chatId)
 * @method static string setWebhook(string $url, array $options = [])
 * @method static string setMyCommands(array $commands)
 * @method static string chat(array $chatData)
 * @method static string isCommand(array $entities)
 */
class Telegram extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return TelegramService::class;
    }
}
