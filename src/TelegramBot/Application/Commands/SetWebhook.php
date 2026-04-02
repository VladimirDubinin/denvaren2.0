<?php

namespace App\TelegramBot\Application\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

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
     */
    public function handle()
    {
        $token = config('telegram.bot_token');
        $url = config('telegram.url');
        Http::post("https://api.telegram.org/bot{$token}/setWebhook", [
            "url" => $url,
            "secret_token" => config('telegram.secret_token'),
            "max_connections" => config('telegram.max_connections'),
            "allowed_updates" => ["message","callback_query","chat_member","my_chat_member"]
        ]);
        $this->info('Вебхук установлен');
    }
}
