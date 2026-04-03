<?php

namespace App\TelegramBot\Application\UseCases;

use App\TelegramBot\Domain\Models\Chat;
use App\TelegramBot\Infrastructure\Facades\Telegram;

final readonly class CommandHandleUseCase
{
    public function execute(Chat $chat, string $text): void
    {
        //TODO: сделать реализацию команд
        Telegram::sendMessage($text, $chat->telegram_id);
    }
}
