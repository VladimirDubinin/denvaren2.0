<?php

namespace App\TelegramBot\Infrastructure\Telegram\Commands;

use App\TelegramBot\Domain\Models\Chat;

interface TelegramCommandInterface
{
    public function handle(Chat $chat): void;
}
