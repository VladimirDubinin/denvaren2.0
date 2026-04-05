<?php

namespace App\TelegramBot\Infrastructure\Repositories;

use App\TelegramBot\Domain\Models\Holiday;
use Illuminate\Support\Collection;

class HolidayRepository
{
    public function list(int $chatId): Collection
    {
       return Holiday::active()
            ->where("chat_id", $chatId)
            ->get();
    }
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

    public function remove(int $id, int $chatId): void
    {
        Holiday::query()->where('chat_id', $chatId)
            ->where('id', $id)
            ->delete();
    }
}
