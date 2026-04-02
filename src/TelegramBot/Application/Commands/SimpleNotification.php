<?php

namespace App\TelegramBot\Application\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\TelegramBot\Domain\Models\Holiday;
use App\TelegramBot\Infrastructure\Repositories\HolidayRepository;

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
    public function handle(HolidayRepository $holidayService): int
    {
        try {
            $holidayId = $this->argument('id');
            $holiday = Holiday::query()->findOrFail($holidayId);
            $chat = TelegraphChat::query()->findOrFail($holiday->chat_id);
            $chat->message("🗓Сегодня {$holiday->date->format('d.m.Y')}, а значит - {$holiday->description}🥳")->send();

            if ($holiday->repeat) {
                $holiday->update([
                   'date' =>  $holiday->date->addYear()
                ]);
            } else {
                $holidayService->deleteHolidayById($holidayId, $chat->id);
            }

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Ошибка отправки уведомления: {$e->getMessage()}");
            Log::debug($e->getTraceAsString());
            return self::FAILURE;
        }
    }
}
