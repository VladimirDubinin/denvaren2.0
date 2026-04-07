<?php

declare(strict_types=1);

namespace App\TelegramBot\Application\TelegramCommands\Commands;

use App\TelegramBot\Application\Request\DTO\TelegramRequestDTO;
use App\TelegramBot\Infrastructure\Facades\Telegram;
use App\TelegramBot\Infrastructure\Telegram\Commands\TelegramCommandInterface;

final class DeleteCommand implements TelegramCommandInterface
{
    public function handle(TelegramRequestDTO $DTO): void
    {
        $chat = $DTO->chat;
        $chat->waiting_add_answer = false;

        Telegram::sendMessage(
            'Укажите дату напоминания, которое хотите удалить',
            $chat->telegram_id
        );

        $chat->waiting_delete_answer = true;
        $chat->save();
    }
}
