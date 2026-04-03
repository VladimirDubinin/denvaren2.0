<?php

namespace App\TelegramBot\Application\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use App\TelegramBot\Infrastructure\Facades\Telegram;

final class WebhookController extends Controller
{
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
    }
}
