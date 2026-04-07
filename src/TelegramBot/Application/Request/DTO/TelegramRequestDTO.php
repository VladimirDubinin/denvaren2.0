<?php

namespace App\TelegramBot\Application\Request\DTO;

use App\TelegramBot\Domain\Models\Chat;
use Spatie\LaravelData\Data;

final class TelegramRequestDTO extends Data
{
    public function __construct(
        public int $updateId,
        public Chat $chat,
        public string $text,
        public bool $isCommand,
        public array $array = [],
    ) {
    }
}
