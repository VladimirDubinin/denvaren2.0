<?php

declare(strict_types=1);

namespace App\TelegramBot\Application\TelegramCommands\Commands;

use App\TelegramBot\Application\Request\DTO\TelegramRequestDTO;
use App\TelegramBot\Domain\Repositories\HolidayRepositoryInterface;
use App\TelegramBot\Infrastructure\Facades\Telegram;
use App\TelegramBot\Infrastructure\Telegram\Commands\TelegramCommandInterface;

final readonly class ListCommand implements TelegramCommandInterface
{
    public function __construct(
        private HolidayRepositoryInterface $holidayRepository
    ) {
    }

    public function handle(TelegramRequestDTO $DTO): void
    {
        $holidays = $this->holidayRepository->list($DTO->chat->id);

        if ($holidays->isEmpty()) {
            Telegram::sendMessage(
                'Не нашёл ни одного напоминания🤷‍♂️ Для добавления используй команду /add',
                $DTO->chat->telegram_id
            );
        } else {
            $html = "Вот ваш список важных дат🤩 \n\n";
            foreach ($holidays as $holiday) {
                $html .= $holiday->date->format('d.m.Y') . ' - ' . $holiday->description . "\n";
            }
            Telegram::sendMessage(
                $html,
                $DTO->chat->telegram_id
            );
        }
    }
}
