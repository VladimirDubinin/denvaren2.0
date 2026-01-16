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

        $chat->reply('Укажите дату праздника, который хотите удалить');
        $chat->waiting_delete_answer = true;
        $chat->save();
    }

    public function deleteHoliday(TelegraphChat $chat, Stringable $text): void
    {
        $date = Carbon::createFromFormat('d.m.Y', $text);
        if (empty($date)) {
            $chat->message('Некорректная дата :( Введите дату праздника в таком формате: ' .
                date('d.m.Y')
            )->send();
        } else {
            $holidays = Holiday::query()
                ->where('date', $date)
                ->where('chat_id', $chat->id)
                ->get();

            if ($holidays->count() > 1) {
                $buttons = [];
                foreach ($holidays as $holiday) {
                    $buttons[] = Button::make($holiday->description)->action('deleteById')->param('id', $holiday->id);
                }
                Telegraph::message('Я обнаружил несколько праздников в эту дату, какой из них мне удалить?')
                    ->keyboard(Keyboard::make()->buttons([
                        $buttons
                    ])
                    )->send();
            } else {
                $holidays->first()?->delete();
                $chat->reply('Дата удалена');
            }
        }

        $chat->waiting_delete_answer = false;
        $chat->save();
    }
}
