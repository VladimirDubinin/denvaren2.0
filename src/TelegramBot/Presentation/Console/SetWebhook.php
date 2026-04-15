<?php

namespace App\TelegramBot\Presentation\Console;

use App\TelegramBot\Infrastructure\Facades\Telegram;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;

class SetWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:set-webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Устанавливает вебхук';

    /**
     * Execute the console command.
     *
     * @throws ConnectionException
     */
    public function handle(): void
    {
        $url = config('telegram.url');
        Telegram::setWebhook($url, [
            "secret_token" => config('telegram.secret_token'),
            "max_connections" => config('telegram.max_connections'),
            "allowed_updates" => ["message","callback_query","chat_member","my_chat_member"]
        ]);
        $this->info('Вебхук установлен');
    }
}
