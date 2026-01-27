<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
    public function handle()
    {
        //
    }
}
