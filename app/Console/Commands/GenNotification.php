<?php

namespace App\Console\Commands;

use App\Http\Services\ChatService;
use App\Models\Holiday;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class GenNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:gen {id : Holiday ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправляет напоминание о событии со сгенерированным поздравлением';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $holidayId = $this->argument('id');
            $holiday = Holiday::query()->findOrFail($holidayId);
            $chat = TelegraphChat::query()->findOrFail($holiday->chat_id);

            $input = "Завтра {$holiday->date->format('d.m.Y')}, а значит у твоего пользователя - {$holiday->description}.
            Нужно написать напоминание и предложить вариант поздравления с праздником.";
            $response = ChatService::requestAI($input);
            if (empty($response)) {
                throw new \Exception('Ошибка AI');
            }

            $chat->message($response)->send();
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Ошибка отправки уведомления: {$e->getMessage()}");
            Log::debug($e->getTraceAsString());
            return self::FAILURE;
        }
    }
}
