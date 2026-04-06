<?php

declare(strict_types=1);

namespace App\TelegramBot\Domain\Repositories;

use App\TelegramBot\Domain\Models\Chat;

interface ChatRepositoryInterface
{
    public function updateOrCreate(array $chatData): Chat;
}
