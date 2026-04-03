<?php

namespace App\TelegramBot\Application\Request\DTO;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

final class TelegramMessageRequestDTO extends Data
{
    public function __construct(
        public int $messageId,
        public TelegramUserRequestDTO $from,
        public TelegramChatRequestDTO $chat,
        public Carbon $date,
        public string $text,
    ) {
    }
}
