<?php

namespace App\TelegramBot\Application\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use App\TelegramBot\Infrastructure\Facades\Telegram;

final class WebhookController extends Controller
{
    private array $message;

    public function __construct(Request $request)
    {
        $this->message = $request->input('message');
    }

    /**
     * Точка входа в телеграм-бота
     *
     * @return void
     */
    public function __invoke(): void
    {
        Log::debug($this->message);
        Telegram::report('Hello, World!');
    }
}
