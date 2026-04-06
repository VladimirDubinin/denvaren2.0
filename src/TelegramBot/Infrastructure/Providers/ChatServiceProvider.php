<?php

declare(strict_types=1);

namespace App\TelegramBot\Infrastructure\Providers;

use App\TelegramBot\Domain\Repositories\ChatRepositoryInterface;
use App\TelegramBot\Infrastructure\Repositories\ChatRepository;
use Illuminate\Support\ServiceProvider;

final class ChatServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ChatRepositoryInterface::class, ChatRepository::class);
    }
}
