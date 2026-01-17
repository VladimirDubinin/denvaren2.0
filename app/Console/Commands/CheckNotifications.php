<?php

namespace App\Console\Commands;

use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Проверяет, по каким датам нужно отправить уведомления';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        // Отправляем простое уведомление в день события
        $holidays = Holiday::active()->where('date', '=', $now->format('Y-m-d'))->get();
        // Отправляем уведомление со сгенерированным поздравлением за день до события
        $holidays = Holiday::active()->where('date', '=', $now->addDay()->format('Y-m-d'))->get();
    }
}
