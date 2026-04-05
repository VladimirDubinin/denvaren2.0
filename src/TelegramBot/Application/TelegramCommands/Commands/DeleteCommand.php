<?php

declare(strict_types=1);

namespace App\TelegramBot\Application\TelegramCommands\Commands;

use App\TelegramBot\Domain\Models\Chat;
use App\TelegramBot\Infrastructure\Facades\Telegram;
use App\TelegramBot\Infrastructure\Telegram\Commands\TelegramCommandInterface;

final class DeleteCommand implements TelegramCommandInterface
{
    public function handle(Chat $chat): void
    {
        $chat->waiting_add_answer = false;

        Telegram::message('Укажите дату напоминания, которое хотите удалить')
            ->send($chat->telegram_id);

        $chat->waiting_delete_answer = true;
        $chat->save();
    }
}
