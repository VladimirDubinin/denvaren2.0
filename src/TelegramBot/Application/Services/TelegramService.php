<?php

namespace App\TelegramBot\Application\Services;

use App\TelegramBot\Application\Request\DTO\TelegramChatRequestDTO;
use App\TelegramBot\Domain\Models\Chat;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

final readonly class TelegramService
{
    /**
     * @throws ConnectionException
     */
    public function send(string $message, int $chatId = 1752193570): void
    {
        $token = config('telegram.bot_token');
        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message
        ]);
    }

    public function chat(array $chatData): Chat
    {
        return Chat::query()->updateOrCreate(
            [
                'telegram_id' => $chatData['id'],
                'username' => $chatData['username'],
            ],
            [
                'first_name' => $chatData['first_name'] ?? '',
                'last_name' => $chatData['last_name'] ?? '',
            ]
        );
    }
}
