<?php

declare(strict_types=1);

namespace App\TelegramBot\Application\TelegramCommands\Commands;

use App\TelegramBot\Domain\Models\Chat;
use App\TelegramBot\Infrastructure\Facades\Telegram;
use App\TelegramBot\Infrastructure\Telegram\Commands\TelegramCommandInterface;
use App\TelegramBot\Infrastructure\Telegram\Keyboard;

final class StartCommand implements TelegramCommandInterface
{
    public function handle(Chat $chat): void
    {
        Telegram::message('Вот, что я умею:')->keyboard(Keyboard::make()
            ->button('Добавить напоминание')->action('add')
            ->button('Удалить напоминание')->action('delete')
            ->button('Список напоминаний')->action('list')
        )->send($chat->telegram_id);
    }
}
