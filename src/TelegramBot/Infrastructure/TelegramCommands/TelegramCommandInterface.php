<?php

namespace App\TelegramBot\Infrastructure\TelegramCommands;

interface TelegramCommandInterface
{
    public function handle(int $chatId): void;
}
