<?php

namespace App\Console\Commands;

use App\Models\Holiday;
use DefStudio\Telegraph\Models\TelegraphChat;
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
            $chat = TelegraphChat::query()->findOrFail($holiday->chat_id);
            $chat->message("🗓Сегодня {$holiday->date->format('d.m.Y')}, а значит - {$holiday->description}🥳")->send();
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Ошибка отправки уведомления: {$e->getMessage()}");
            Log::debug($e->getTraceAsString());
            return self::FAILURE;
        }
    }
}
