<?php

namespace Src\TelegramBot\Application\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

final readonly class TelegramService
{
    private const CHAT_ID = 1752193570;

    /**
     * @throws ConnectionException
     */
    public function report(string $message): void
    {
        $token = config('telegram.bot_token');
        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => self::CHAT_ID,
            'text' => $message
        ]);
    }
}
