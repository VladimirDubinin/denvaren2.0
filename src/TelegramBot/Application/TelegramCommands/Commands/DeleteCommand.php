<?php

namespace App\TelegramBot\Application\TelegramCommands\Commands;

use App\TelegramBot\Infrastructure\TelegramCommands\TelegramCommandInterface;

final class DeleteCommand implements TelegramCommandInterface
{
    public function handle(int $chatId): void
    {

    }
}
