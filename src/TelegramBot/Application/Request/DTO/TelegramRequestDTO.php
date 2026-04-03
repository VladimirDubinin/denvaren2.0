<?php

namespace App\TelegramBot\Application\Request\DTO;

use Spatie\LaravelData\Data;

final class TelegramRequestDTO extends Data
{
    public function __construct(
        public int $updateId,
        public TelegramMessageRequestDTO $message,
    ) {
    }
}
