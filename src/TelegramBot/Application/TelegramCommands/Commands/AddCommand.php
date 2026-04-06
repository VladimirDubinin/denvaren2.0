<?php

declare(strict_types=1);

namespace App\TelegramBot\Application\TelegramCommands\Commands;

use App\TelegramBot\Domain\Models\Chat;
use App\TelegramBot\Domain\Repositories\HolidayRepositoryInterface;
use App\TelegramBot\Infrastructure\Facades\Telegram;
use App\TelegramBot\Infrastructure\Telegram\Commands\TelegramCommandInterface;

final readonly class AddCommand implements TelegramCommandInterface
{
    public function __construct(
        private HolidayRepositoryInterface $holidayRepository
    ) {
    }

    public function handle(Chat $chat): void
    {
        $chat->waiting_delete_answer = false;

        $this->holidayRepository->add($chat->id);
        Telegram::message("Больше праздников - больше положительных эмоций!\n\nУкажи дату предстоящего события в таком формате \"дд.мм.гггг\"")
            ->send($chat->telegram_id);

        $chat->waiting_add_answer = true;
        $chat->save();
    }
}
