<?php

declare(strict_types=1);

namespace App\TelegramBot\Application\UseCases;

use App\TelegramBot\Application\Request\DTO\TelegramRequestDTO;
use App\TelegramBot\Domain\Models\Holiday;
use App\TelegramBot\Domain\Exceptions\AddDateException;
use App\TelegramBot\Domain\Models\Chat;
use App\TelegramBot\Domain\Repositories\HolidayRepositoryInterface;
use App\TelegramBot\Infrastructure\Facades\Telegram;
use Carbon\Carbon;

final readonly class MessageHandleUseCase
{
    public function  __construct(
        private HolidayRepositoryInterface $holidayRepository
    ) {
    }

    public function execute(TelegramRequestDTO $DTO): void
    {
        if ($DTO->chat->waiting_add_answer) {
            $this->updateHoliday($DTO->chat, $DTO->text);
        } elseif ($DTO->chat->waiting_delete_answer) {
            $this->deleteHoliday($DTO->chat, $DTO->text);
        } else {
            Telegram::sendMessage(
                "Извини, у меня нет времени на поболтать😎 Воспользуйся одной из команд",
                $DTO->chat->telegram_id
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
        if (!Carbon::canBeCreatedFromFormat($text, 'd.m.Y')) {
            $message = 'Некорректная дата :( Введи дату напоминания в таком формате "дд.мм.гггг"';
        } else {
            $date = Carbon::createFromFormat('d.m.Y', $text);
            $holiday = Holiday::query()
                ->where('date', $date->format('Y-m-d'))
                ->where('chat_id', $chat->id)
                ->first();

            if ($holiday) {
                $holiday->delete();
                $message = 'Напоминание удалено🥲';
            } else {
                $message = 'Напоминание не найдено😢';
            }
        }

        Telegram::sendMessage(
            $message,
            $chat->telegram_id
        );
        $chat->waiting_delete_answer = false;
        $chat->save();
    }
}
