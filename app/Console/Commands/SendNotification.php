<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправляет уведомление в чат';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
