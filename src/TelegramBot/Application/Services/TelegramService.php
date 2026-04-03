<?php

namespace App\TelegramBot\Application\Services;

use App\TelegramBot\Domain\Models\Chat;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

final readonly class TelegramService
{
    /**
     * @throws ConnectionException
     */
    private function request(string $method, array $payload = []): void
    {
        $token = config('telegram.bot_token');
        Http::post("https://api.telegram.org/bot{$token}/{$method}", $payload);
    }

    /**
     * @throws ConnectionException
     */
    public function sendMessage(string $message, ?int $chatId = null): void
    {
        $this->request(__FUNCTION__, [
            'chat_id' => $chatId ?? config('telegram.chat_id'),
            'text' => $message
        ]);
    }

    /**
     * @throws ConnectionException
     */
    public function setWebhook(string $url, array $options = []): void
    {
        $payload = array_merge([$url], $options);
        $this->request(__FUNCTION__, $payload);
    }

    /**
     * @throws ConnectionException
     */
    public function setMyCommands(array $commands): void
    {
        $this->request(__FUNCTION__, ['commands' => $commands]);
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

     public function registerBotCommands(array $commands): void
     {

     }

    public function isCommand(array $entities = []): bool
    {
        return !empty($entities)
            && isset($entities[0]['type'])
            && $entities[0]['type'] === 'bot_command';
    }
}
