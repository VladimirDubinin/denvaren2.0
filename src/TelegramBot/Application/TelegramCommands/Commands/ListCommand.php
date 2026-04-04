<?php

namespace App\TelegramBot\Application\TelegramCommands\Commands;

use App\TelegramBot\Infrastructure\Facades\Telegram;
use App\TelegramBot\Infrastructure\Telegram\Commands\TelegramCommandInterface;

final class ListCommand implements TelegramCommandInterface
{
    public function handle(int $chatId): void
    {
        Telegram::sendMessage('Команда /list', $chatId);
    }
}
