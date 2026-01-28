<?php

namespace App\Http\Services;

use App\Models\Holiday;
use Carbon\Carbon;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Stringable;

class HolidayService
{
    /**
     * Добавление напоминаний
     *
     * @param TelegraphChat $chat
     * @return void
     */
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
                "Больше праздников - больше положительных эмоций!\n\nУкажи дату предстоящего события в таком формате \"дд.мм.гггг\""
            )->send();
        } elseif (empty($newHoliday->date)) {
            $chat->message(
                'Продолжим добавление напоминания! Введи дату предстоящего события в таком формате: ' .
                date('d.m.Y')
            )->send();
        } else {
            $chat->message(
                'Продолжим добавление напоминания на ' . $newHoliday->date->format('d.m.Y') .
                '! Введи описание события, либо удали черновик и начни добавление заново'
            )->keyboard(Keyboard::make()
                ->button('Удалить')
                ->action('deleteById')
                ->param('id', $newHoliday->id)
            )->send();
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
            $result = $this->setDate($currentHoliday, $text);
            $chat->message($result)->send();
        } else {
            $currentHoliday->update(['description' => $text]);
            $chat->waiting_add_answer = false;
            $chat->save();
            $chat->message('Я всё записал, ожидай напоминание ;)')->send();
        }
    }

    private function setDate(Holiday $holiday, Stringable $text): string
    {
        if (Carbon::canBeCreatedFromFormat('d.m.Y', $text)) {
            $date = Carbon::createFromFormat('d.m.Y', $text);
            if ($date < Carbon::now()) {
                $result = 'Введённая дата не должна быть раньше ' . date('d.m.Y');
            } else {
                $holiday->update(['date' => $date]);
                $result = 'Окей, теперь добавь описание праздника, чтобы я смог предложить хорошее поздравление';
            }
        } else {
            $result = 'Некорректная дата :( Введи дату напоминания в формате "дд.мм.гггг"';
        }

        return $result;
    }

    /**
     * Удаление напоминаний
     *
     * @param TelegraphChat $chat
     * @return void
     */
    public function startDeletingHoliday(TelegraphChat $chat): void
    {
        $chat->waiting_add_answer = false;

        $chat->message('Укажите дату напоминания, которое хотите удалить')->send();
        $chat->waiting_delete_answer = true;
        $chat->save();
    }

    public function deleteHoliday(TelegraphChat $chat, Stringable $text): void
    {
        if (!Carbon::canBeCreatedFromFormat('d.m.Y', $text)) {
            $chat->message('Некорректная дата :( Введи дату напоминания в таком формате "дд.мм.гггг"')->send();
        } else {
            $date = Carbon::createFromFormat('d.m.Y', $text);
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
                $chat->message('Я обнаружил несколько напоминаний в эту дату, какое из них мне удалить?')
                    ->keyboard($keyboard)
                    ->send();
            } elseif ($holidays->count() === 1) {
                $holiday = $holidays->first();
                $chat->message('Уверены, что день ' . $holiday->date->format('d.m.Y') . ' тебе больше не важен?')
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
