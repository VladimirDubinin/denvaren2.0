<?php

declare(strict_types=1);

namespace App\TelegramBot\Infrastructure\Providers;

use App\TelegramBot\Domain\Repositories\HolidayRepositoryInterface;
use App\TelegramBot\Infrastructure\Repositories\HolidayRepository;
use Illuminate\Support\ServiceProvider;

final class HolidayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(HolidayRepositoryInterface::class, HolidayRepository::class);
    }
}
