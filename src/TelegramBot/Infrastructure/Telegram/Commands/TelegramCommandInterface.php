<?php

namespace App\TelegramBot\Infrastructure\Telegram\Commands;

use App\TelegramBot\Domain\Request\DTO\TelegramRequestDTO;

interface TelegramCommandInterface
{
    public function handle(TelegramRequestDTO $DTO): void;
}
