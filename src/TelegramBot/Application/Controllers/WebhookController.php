<?php

namespace App\TelegramBot\Application\Controllers;

use App\TelegramBot\Application\Request\DTO\TelegramRequestDTO;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use App\TelegramBot\Infrastructure\Facades\Telegram;

final class WebhookController extends Controller
{
    /**
     * Точка входа в телеграм-бота
     *
     * @param TelegramRequestDTO $requestDTO
     * @return void
     */
    public function __invoke(TelegramRequestDTO $requestDTO): void
    {
        if (config('app.debug')) {
            Log::debug(print_r($requestDTO->all(), true));
        }

        $chat = Telegram::chat($requestDTO->message->chat);
    }
}
