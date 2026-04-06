<?php

declare(strict_types=1);

namespace App\TelegramBot\Application\UseCases;

use App\TelegramBot\Domain\Exceptions\AddDateException;
use App\TelegramBot\Domain\Models\Chat;
use App\TelegramBot\Domain\Repositories\HolidayRepositoryInterface;
use App\TelegramBot\Infrastructure\Facades\Telegram;

final readonly class MessageHandleUseCase
{
    public function  __construct(
        private HolidayRepositoryInterface $holidayRepository
    ) {
    }

    public function execute(Chat $chat, string $text): void
    {
        if ($chat->waiting_add_answer) {
            $this->updateHoliday($chat, $text);
        } elseif ($chat->waiting_delete_answer) {
            $this->deleteHoliday($chat, $text);
        } else {
            Telegram::sendMessage(
                "Извини, у меня нет времени на поболтать😎 Воспользуйся одной из команд",
                $chat->telegram_id
            );
        }
    }

    private function updateHoliday(Chat $chat, string $text): void
    {
        $currentHoliday = $this->holidayRepository->getCurrent($chat->id);
        if (empty($currentHoliday)) {
            $message = "Возникла непредвиденная ошибка :( Попробуйте заново";
        } elseif (empty($currentHoliday->date)) {
            try {
                $message = $this->holidayRepository->setDate($currentHoliday, $text);
            } catch (AddDateException $e) {
                $message = $e->getMessage();
            }
        } else {
            $currentHoliday->update(['description' => $text]);
            $chat->waiting_add_answer = false;
            $chat->save();
            $message = 'Я всё записал, ожидай напоминание 😉';

        }

        Telegram::sendMessage(
            $message,
            $chat->telegram_id
        );
    }

    private function deleteHoliday(Chat $chat, string $text): void
    {
        //TODO: Реализовать удаление события
    }
}
