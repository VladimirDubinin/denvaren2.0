<?php

namespace App\TelegramBot\Application\ConsoleCommands;

use App\TelegramBot\Domain\Models\Chat;
use App\TelegramBot\Domain\Models\Holiday;
use App\TelegramBot\Infrastructure\Facades\Telegram;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SimpleNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:simple {id : Holiday ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправляет простое напоминание о событии в чат';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $holidayId = $this->argument('id');
            $holiday = Holiday::query()->findOrFail($holidayId);
            $chat = Chat::query()->findOrFail($holiday->chat_id);
            Telegram::sendMessage(
                "🗓Сегодня {$holiday->date->format('d.m.Y')}, а значит - {$holiday->description}🥳",
                $chat->telegram_id
            );

            $holiday->update([
                'date' =>  $holiday->date->addYear()
            ]);

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Ошибка отправки уведомления: {$e->getMessage()}");
            Log::debug($e->getTraceAsString());
            return self::FAILURE;
        }
    }
}
