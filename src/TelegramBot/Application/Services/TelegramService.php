<?php

namespace App\TelegramBot\Application\Services;

use App\TelegramBot\Domain\Models\Chat;
use App\TelegramBot\Infrastructure\Telegram\Keyboard;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class TelegramService
{
    private string $message;
    private array $replyMarkup = [];

    /**
     * @throws ConnectionException
     */
    private function request(string $method, array $payload = []): void
    {
        $token = config('telegram.bot_token');
        if (config('app.debug')) {
            Log::debug('Request: ' . PHP_EOL . print_r($payload, true));
        }
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

    /**
     * Создает или обновляет данные чата из запроса
     *
     * @param array $chatData
     * @return Chat
     */
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

    /**
     * Проверяет, является ли сообщение от бота командой
     *
     * @param array|null $message
     * @return bool
     */
    public function isCommand(array|null $message): bool
    {
        return !empty($message)
            && isset($message['entities'][0]['type'])
            && $message['entities'][0]['type'] === 'bot_command';
    }

    public function message(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function keyboard(Keyboard $keyboard): self
    {
        $this->replyMarkup = $keyboard->toArray();
        return $this;
    }

    public function send(int $chatId): void
    {
        $payload = [
            'chat_id' => $chatId,
            'text' => $this->message
        ];

        if (count($this->replyMarkup) > 0) {
            $payload['reply_markup'] = $this->replyMarkup;
        }

        $this->request('sendMessage', $payload);
    }
}
