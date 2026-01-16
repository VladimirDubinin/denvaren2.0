<?php

namespace App\Http\Services;

use App\Models\Holiday;
use Carbon\Carbon;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Stringable;

class AddHolidayService
{
    public function addNewHoliday(TelegraphChat $chat): void
    {
        $chat->waiting_delete_answer = false;

        // Смотрим, есть ли даты в процессе добавления
        $newHoliday = $this->getCurrentHoliday($chat->id);
        // Если нет, создаём новую
        if (empty($newHoliday)) {
            $holiday = new Holiday();
            $holiday->chat_id = $chat->id;
            $holiday->save();

            $chat->html(
                "Больше праздников - больше положительных эмоций!\n\nУкажите дату напоминания в таком формате: "
                . date('d.m.Y')
            )->send();
        } elseif (empty($newHoliday->date)) {
            $chat->message(
                'Продолжим добавление напоминания! Введите дату праздника в таком формате: ' .
                date('d.m.Y')
            )->send();
        } else {
            $chat->message('Продолжим добавление напоминания! Введите описание праздника')->send();
        }

        $chat->waiting_add_answer = true;
        $chat->save();
    }

    public function updateNewHoliday(TelegraphChat $chat, Stringable $text): void
    {
        $currentHoliday = $this->getCurrentHoliday($chat->id);
        if (empty($currentHoliday)) {
            $chat->message('Возникла непредвиденная ошибка :( Попробуйте заново')->send();
            Log::debug(
                '[' . date('d.m.Y H:i:s') . '] Ошибка добавления напоминания. ID чата: ' . $chat->id
            );
        } elseif (empty($currentHoliday->date)) {
            $date = Carbon::createFromFormat('d.m.Y', $text);
            if (empty($date)) {
                $chat->message('Некорректная дата :( Введите дату напоминания в таком формате: ' .
                    date('d.m.Y')
                )->send();
            } else {
                $currentHoliday->update(['date' => $date]);
                $chat->message('Окей, теперь добавьте описание праздника, чтобы я смог предложить хорошее поздравление')->send();
            }
        } else {
            $currentHoliday->update(['description' => $text]);
            $chat->waiting_add_answer = false;
            $chat->save();
            $chat->message('Я всё записал, ожидайте напоминание ;)')->send();
        }
    }

    private function getCurrentHoliday(int $chat_id): Holiday|null
    {
        return Holiday::query()->where('chat_id', $chat_id)
            ->where(function ($query) {
                $query->whereNull('date')
                    ->orWhereNull('description');
            })
            ->orderBy('id', 'DESC')
            ->first();
    }
}
