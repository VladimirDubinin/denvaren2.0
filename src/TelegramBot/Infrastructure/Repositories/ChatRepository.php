<?php

declare(strict_types=1);

namespace App\TelegramBot\Infrastructure\Repositories;

use App\TelegramBot\Domain\Models\Chat;
use App\TelegramBot\Domain\Repositories\ChatRepositoryInterface;

class ChatRepository implements ChatRepositoryInterface
{
    /**
     * Создает или обновляет данные чата из запроса
     *
     * @param array $chatData
     * @return Chat
     */
    public function updateOrCreate(array $chatData): Chat
    {
        return Chat::query()->updateOrCreate(
            [
                'telegram_id' => $chatData['id'],
                'username' => $chatData['username'],
            ],
            [
                'first_name' => $chatData['first_name'] ?? '',
                'last_name' => $chatData['last_name'] ?? '',
            ]
        );
    }
}
