<?php

declare(strict_types=1);

namespace App\TelegramBot\Application\TelegramCommands\Commands;

use App\TelegramBot\Domain\Models\Chat;
use App\TelegramBot\Domain\Models\Holiday;
use App\TelegramBot\Infrastructure\Facades\Telegram;
use App\TelegramBot\Infrastructure\Repositories\HolidayRepository;
use App\TelegramBot\Infrastructure\Telegram\Commands\TelegramCommandInterface;

final readonly class ListCommand implements TelegramCommandInterface
{
    public function __construct(
        private HolidayRepository $holidayRepository
    ) {
    }

    public function handle(Chat $chat): void
    {
        $holidays = $this->holidayRepository->list($chat->id);

        if ($holidays->isEmpty()) {
            Telegram::message('Не нашёл ни одного напоминания🤷‍♂️ Для добавления используй команду /add')->send($chat->telegram_id);
        } else {
            $html = "Вот ваш список важных дат🤩 \n\n";
            foreach ($holidays as $holiday) {
                $html .= $holiday->date->format('d.m.Y') . ' - ' . $holiday->description . "\n";
            }
            Telegram::message($html)->send($chat->telegram_id);
        }
    }
}
