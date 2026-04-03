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
    public function __construct(
        private readonly MessageHandleUseCase $messageHandleUseCase,
        private readonly CommandHandleUseCase $commandHandleUseCase,
    ) {
    }

    /**
     * Точка входа в телеграм-бота
     *
     * @param Request $request
     * @return void
     */
    public function __invoke(Request $request): void
    {
        if (config('app.debug')) {
            Log::debug(print_r($request->all(), true));
        }

        $chat = Telegram::chat($request->message['chat']);

        if (Telegram::isCommand($request->entities)) {
            $this->commandHandleUseCase->execute($chat, $request->text);
        } else {
            $this->messageHandleUseCase->execute($chat, $request->text);
        }
    }
}
