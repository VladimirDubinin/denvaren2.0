<?php

declare(strict_types=1);

namespace App\TelegramBot\Application\TelegramCommands\Commands;

use App\TelegramBot\Domain\Repositories\HolidayRepositoryInterface;
use App\TelegramBot\Domain\Request\DTO\TelegramRequestDTO;
use App\TelegramBot\Infrastructure\Facades\Telegram;
use App\TelegramBot\Infrastructure\Telegram\Commands\TelegramCommandInterface;

final readonly class AddCommand implements TelegramCommandInterface
{
    public function __construct(
        private HolidayRepositoryInterface $holidayRepository
    ) {
    }

    public function handle(TelegramRequestDTO $DTO): void
    {
        $chat = $DTO->chat;
        $chat->waiting_delete_answer = false;

        $this->holidayRepository->add($chat->id);
        Telegram::sendMessage(
            "Больше праздников - больше положительных эмоций!\n\nУкажи дату предстоящего события в таком формате \"дд.мм.гггг\"",
            $chat->telegram_id
        );

        $chat->waiting_add_answer = true;
        $chat->save();
    }
}
