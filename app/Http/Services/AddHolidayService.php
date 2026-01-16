<?php

namespace App\Http\Services;

use App\Models\Holiday;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Support\Facades\Log;

class AddHolidayService
{
    public function addNewHoliday(TelegraphChat $chat): void
    {
        // Смотрим, есть ли даты в процессе добавления
        $newHoliday = Holiday::query()->where('chat_id', $chat->id)
            ->where(function ($query) {
                $query->whereNull('date')
                    ->orWhereNull('description')
                    ->orWhereNull('repeat');
            })
            ->orderBy('id', 'DESC')
            ->first();
        // Если нет, создаём новую
        if (empty($newHoliday)) {
            $holiday = new Holiday();
            $holiday->chat_id = $chat->id;
            $holiday->save();

            $chat->html('Отлично, больше праздников - больше положительных эмоций!<br><br>Укажите дату праздника')->send();
        } elseif (empty($newHoliday->date)) {
            $chat->message('Продолжим добавление праздника! Введите дату праздника')->send();
        } elseif (empty($newHoliday->description)) {
            $chat->message('Продолжим добавление праздника! Введите описание праздника')->send();
        } elseif (empty($newHoliday->repeat)) {
            $chat->message('Продолжим добавление праздника! Повторять ли уведомление каждый год?')->send();
        } else {
            $chat->message('Непредвиденная ошибка!')->send();
            Log::debug(
                '[' . date('d.m.Y H:i:s') . '] Ошибка добавления праздника ID: ' . $newHoliday->id
            );
        }

        $chat->waiting_add_answer = true;
        $chat->save();
    }

    public function addHolidayDate(TelegraphChat $chat): void
    {

    }

    public function addHolidayDesc(TelegraphChat $chat): void
    {

    }

    public function addHolidayRepeat(TelegraphChat $chat): void
    {

    }
}
