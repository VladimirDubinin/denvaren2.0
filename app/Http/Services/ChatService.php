<?php

namespace App\Http\Services;

use Carbon\Carbon;

class ChatService
{
    public static function checkDate($text): Carbon|null
    {
        if (Carbon::canBeCreatedFromFormat('d.m.Y', $text)) {
            return Carbon::createFromFormat('d.m.Y', $text);
        }

        return null;
    }
}
