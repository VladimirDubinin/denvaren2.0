<?php

namespace App\TelegramBot\Presentation\Console;

use App\TelegramBot\Infrastructure\Facades\Telegram;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;

class SetMyCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:set-commands';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Устанавливает список команд меню бота';

    /**
     * Execute the console command.
     *
     * @throws ConnectionException
     */
    public function handle(): void
    {
        $commands = config('telegram.bot_commands');
        Telegram::setMyCommands($commands);
        $this->info('Список команд установлен');
    }
}
