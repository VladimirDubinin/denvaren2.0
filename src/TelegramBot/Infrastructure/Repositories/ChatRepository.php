<?php

namespace App\TelegramBot\Infrastructure\Repositories;

use App\TelegramBot\Domain\Models\Chat;

class ChatRepository
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
