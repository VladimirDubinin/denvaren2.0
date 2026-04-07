<?php

namespace App\TelegramBot\Infrastructure\Telegram\Commands;

use App\TelegramBot\Application\Request\DTO\TelegramRequestDTO;
use App\TelegramBot\Domain\Models\Chat;

interface TelegramCommandInterface
{
    public function handle(TelegramRequestDTO $DTO): void;
}
