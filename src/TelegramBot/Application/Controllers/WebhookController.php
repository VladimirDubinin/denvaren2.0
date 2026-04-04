<?php

namespace App\TelegramBot\Application\Controllers;

use App\TelegramBot\Application\UseCases\MessageHandleUseCase;
use App\TelegramBot\Application\UseCases\CommandHandleUseCase;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use App\TelegramBot\Infrastructure\Facades\Telegram;

final class WebhookController extends Controller
{
    private array $message;

    public function __construct(
        private readonly MessageHandleUseCase $messageHandleUseCase,
        private readonly CommandHandleUseCase $commandHandleUseCase,
        Request $request
    ) {
        if (config('app.debug')) {
            Log::debug(print_r($request->all(), true));
        }
        $this->message = $request->input('message');
    }

    /**
     * Точка входа в телеграм-бота
     *
     * @return void
     */
    public function __invoke(): void
    {
        $chat = Telegram::chat($this->message['chat']);

        if (Telegram::isCommand($this->message)) {
            $this->commandHandleUseCase->execute($chat->telegram_id, $this->message['text']);
        } else {
            $this->messageHandleUseCase->execute($chat->telegram_id, $this->message['text']);
        }
    }
}
