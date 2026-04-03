<?php

namespace App\TelegramBot\Application\Request\DTO;

use Spatie\LaravelData\Data;

final class TelegramUserRequestDTO extends Data
{
    public function __construct(
        public int $id,
        public bool $isBot,
        public string $firstName,
        public string $userName,
        public ?string $lastName,
        public string $languageCode,
        public bool $isPremium
    ) {
    }
}
