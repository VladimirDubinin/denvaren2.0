<?php

namespace App\TelegramBot\Application\TelegramCommands\Commands;

use App\TelegramBot\Domain\Models\Chat;
use App\TelegramBot\Infrastructure\Facades\Telegram;
use App\TelegramBot\Infrastructure\Telegram\Commands\TelegramCommandInterface;

final class AddCommand implements TelegramCommandInterface
{
    public function handle(Chat $chat): void
    {
        Telegram::sendMessage('Команда /add', $chat->telegram_id);
    }
}
