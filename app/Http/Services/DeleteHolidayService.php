<?php

namespace App\Http\Services;

use App\Models\Holiday;
use Carbon\Carbon;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Support\Stringable;

class DeleteHolidayService
{
    public function startDeletingHoliday(TelegraphChat $chat): void
    {
        $chat->waiting_add_answer = false;

        $chat->message('Укажите дату напоминания, которое хотите удалить')->send();
        $chat->waiting_delete_answer = true;
        $chat->save();
    }

    public function deleteHoliday(TelegraphChat $chat, Stringable $text): void
    {
        $date = Carbon::createFromFormat('d.m.Y', $text);
        if (empty($date)) {
            $chat->message('Некорректная дата :( Введите дату напоминания в таком формате: ' .
                date('d.m.Y')
            )->send();
        } else {
            $holidays = Holiday::query()
                ->where('date', $date->format('Y-m-d'))
                ->where('chat_id', $chat->id)
                ->get();

            if ($holidays->count() > 1) {
                $keyboard = Keyboard::make();
                foreach ($holidays as $holiday) {
                    $keyboard->button($holiday->description)
                        ->action('deleteById')
                        ->param('id', $holiday->id);
                }
                $chat->message('Я обнаружил несколько напоминаний в эту дату, какой из них мне удалить?')
                    ->keyboard($keyboard)
                    ->send();
            } elseif ($holidays->count() === 1) {
                $holiday = $holidays->first();
                $chat->message('Вы уверены, что хотите удалить напоминание на ' . $holiday->date->format('d.m.Y') . '?')
                    ->keyboard(Keyboard::make()
                        ->button('Да')->action('deleteById')->param('id', $holiday->id)
                        ->button('Нет')->action('stopDeleting')
                    )->send();
            } else {
                $chat->message('Напоминание не найдено')->send();
            }
        }

        $chat->waiting_delete_answer = false;
        $chat->save();
    }

    public function deleteHolidayById(int $holiday_id, int $chat_id): bool
    {
        return Holiday::query()
            ->where('id', $holiday_id)
            ->where('chat_id', $chat_id)
            ->delete();
    }
}
