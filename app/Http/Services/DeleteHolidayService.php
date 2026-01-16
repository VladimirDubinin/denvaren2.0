<?php

namespace App\Http\Services;

use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Support\Stringable;

class DeleteHolidayService
{
    public function deleteHoliday(TelegraphChat $chat, Stringable $text): void
    {
        $chat->update(['waiting_delete_answer' => false]);
    }
}
