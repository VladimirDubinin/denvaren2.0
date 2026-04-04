<?php

namespace App\TelegramBot\Infrastructure\Telegram\Commands;

interface TelegramCommandInterface
{
    public function handle(int $chatId): void;
}
