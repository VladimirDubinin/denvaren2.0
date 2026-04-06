<?php

declare(strict_types=1);

namespace App\TelegramBot\Domain\Repositories;

use App\TelegramBot\Domain\Models\Holiday;
use Illuminate\Support\Collection;

interface HolidayRepositoryInterface
{
    public function getCurrent(int $chatId): Holiday|null;

    public function list(int $chatId): Collection;

    public function add(int $chatId): Holiday|null;

    public function setDate(Holiday $holiday, string $text): string;

    public function remove(int $id, int $chatId): void;
}
