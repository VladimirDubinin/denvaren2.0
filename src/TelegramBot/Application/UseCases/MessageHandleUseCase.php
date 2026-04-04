<?php

namespace App\TelegramBot\Application\UseCases;

use App\TelegramBot\Infrastructure\Facades\Telegram;

final readonly class MessageHandleUseCase
{
    public function execute(int $chatId, string $text): void
    {
        //TODO: сделать реализацию ответа на сообщения
        Telegram::sendMessage($text, $chatId);
    }
}
