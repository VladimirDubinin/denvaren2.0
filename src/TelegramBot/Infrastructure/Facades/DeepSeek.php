<?php

namespace App\TelegramBot\Infrastructure\Facades;

use Illuminate\Support\Facades\Facade;
use App\TelegramBot\Application\Services\DeepSeekService;

class DeepSeek extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return DeepSeekService::class;
    }
}
