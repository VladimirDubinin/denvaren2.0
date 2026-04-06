<?php

declare(strict_types=1);

namespace App\TelegramBot\Infrastructure\Repositories;

use App\TelegramBot\Domain\Exceptions\AddDateException;
use App\TelegramBot\Domain\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class HolidayRepository
{
    /**
     * Возвращает событие, добавление которого идёт в данный момент
     *
     * @param int $chatId
     * @return Holiday|null
     */
    public function getCurrent(int $chatId): Holiday|null
    {
        return Holiday::query()->where('chat_id', $chatId)
            ->where(function ($query) {
                $query->whereNull('date')
                    ->orWhereNull('description');
            })
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function list(int $chatId): Collection
    {
       return Holiday::active()
            ->where("chat_id", $chatId)
            ->get();
    }

    /**
     * Добавляет новое события. Если есть другие незавершенные события - удаляет их
     *
     * @param int $chatId
     * @return Holiday|null
     */
    public function add(int $chatId): Holiday|null
    {
        Holiday::query()->where('chat_id', $chatId)
            ->where(function ($query) {
                $query->whereNull('date')
                    ->orWhereNull('description');
            })
            ->delete();

        return Holiday::query()->create([
            'chat_id' => $chatId,
        ]);
    }

    /**
     * Функция устанавливает дату события или выбрасывает ошибку
     *
     * @throws AddDateException
     */
    public function setDate(Holiday $holiday, string $text): string
    {
        if (Carbon::canBeCreatedFromFormat($text,'d.m.Y')) {
            $date = Carbon::createFromFormat('d.m.Y', $text);
            if ($date < Carbon::now()) {
                throw AddDateException::earlyDate();
            } else {
                $holiday->update(['date' => $date]);
                return 'Окей, теперь добавь описание праздника, чтобы я смог предложить хорошее поздравление';
            }
        } else {
            throw AddDateException::incorrectDate();
        }
    }

    public function remove(int $id, int $chatId): void
    {
        Holiday::query()->where('chat_id', $chatId)
            ->where('id', $id)
            ->delete();
    }
}
