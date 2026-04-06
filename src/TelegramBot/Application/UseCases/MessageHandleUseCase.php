<?php

declare(strict_types=1);

namespace App\TelegramBot\Application\UseCases;

use App\TelegramBot\Domain\Exceptions\AddDateException;
use App\TelegramBot\Domain\Models\Chat;
use App\TelegramBot\Infrastructure\Facades\Telegram;
use App\TelegramBot\Infrastructure\Repositories\HolidayRepository;

final readonly class MessageHandleUseCase
{
    public function  __construct(
        private HolidayRepository $holidayRepository
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
            Telegram::sendMessage(
                "Возникла непредвиденная ошибка :( Попробуйте заново",
                $chat->telegram_id
            );
        } elseif (empty($currentHoliday->date)) {
            try {
                $result = $this->holidayRepository->setDate($currentHoliday, $text);
            } catch (AddDateException $e) {
                $result = $e->getMessage();
            } finally {
                Telegram::sendMessage(
                    $result,
                    $chat->telegram_id
                );
            }
        } else {
            $currentHoliday->update(['description' => $text]);
            $chat->waiting_add_answer = false;
            $chat->save();
        }
    }

    private function deleteHoliday(Chat $chat, string $text): void
    {
        //TODO: Реализовать удаление события
    }
}
