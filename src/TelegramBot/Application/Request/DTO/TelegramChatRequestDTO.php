<?php

namespace App\TelegramBot\Application\Request\DTO;

use Spatie\LaravelData\Data;

final class TelegramChatRequestDTO extends Data
{
    public function __construct(
        public int $id,
        public string $firstName,
        public ?string $lastName,
        public string $userName,
        public string $type,
    ) {
    }
}
