<?php

namespace App\TelegramBot\Infrastructure\Facades;

use App\TelegramBot\Application\Services\TelegramService;
use App\TelegramBot\Domain\Models\Chat;
use App\TelegramBot\Domain\Request\DTO\TelegramRequestDTO;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void sendMessage(string $message, ?int $chatId)
 * @method static void setWebhook(string $url, array $options = [])
 * @method static void setMyCommands(array $commands)
 * @method static Chat chat(array $chatData)
 * @method static bool isCommand(array $entities)
 * @method static TelegramRequestDTO fromArray(array $entities)
 */
class Telegram extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return TelegramService::class;
    }
}
