<?php

namespace App\TelegramBot\Application\Request\DTO;

use App\TelegramBot\Domain\Models\Chat;
use Spatie\LaravelData\Data;

final class TelegramRequestDTO extends Data
{
    public function __construct(
        public Chat $chat,
        public string $text,
        public bool $isCommand,
    ) {
    }
}
