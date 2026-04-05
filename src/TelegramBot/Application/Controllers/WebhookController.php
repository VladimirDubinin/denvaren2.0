<?php

declare(strict_types=1);

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
            Log::debug('Response: ' . PHP_EOL . print_r($request->all(), true));
        }

        $DTO = Telegram::fromArray($request->all());

        if ($DTO->isCommand) {
            $this->commandHandleUseCase->execute($DTO->chat, $DTO->text);
        } else {
            $this->messageHandleUseCase->execute($DTO->chat->telegram_id, $DTO->text);
        }
    }
}
