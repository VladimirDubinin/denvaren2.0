<?php

declare(strict_types=1);

namespace App\TelegramBot\Application\Services;

use App\TelegramBot\Application\Request\DTO\TelegramRequestDTO;
use App\TelegramBot\Infrastructure\Repositories\ChatRepository;
use App\TelegramBot\Infrastructure\Telegram\Keyboard;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

final class TelegramService
{
    private string $message;
    private array $replyMarkup = [];
    private string $endpoint = 'https://api.telegram.org';

    public function __construct(
        private readonly ChatRepository $chatRepository,
    ) {
    }

    /**
     * @throws ConnectionException
     */
    private function request(string $method, array $payload = []): void
    {
        $token = config('telegram.bot_token');
        Http::withUrlParameters([
            'endpoint' => $this->endpoint,
            'bot' => "bot{$token}",
            'method' => $method,
        ])->post('{+endpoint}/{bot}/{method}', $payload);
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
     * Возвращает DTO из массива запроса от телеграм-бота
     *
     * @param array $array
     * @return TelegramRequestDTO
     */
    public function fromArray(array $array): TelegramRequestDTO
    {
        if (array_key_exists('callback_query', $array)) {
            $chatArray = $array['callback_query']['message']['chat'];
            $data = json_decode($array['callback_query']['data'], true);
            $text = (is_array($data) && array_key_exists('action', $data)) ? $data['action'] : '';
            $isCommand = true;
        } else {
            $chatArray = $array['message']['chat'];
            $text = $array['message']['text'];
            $isCommand = $this->isCommand($array['message']);
        }

        $chat = $this->chatRepository->updateOrCreate($chatArray);
        return new TelegramRequestDTO($chat, $text, $isCommand);
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
