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
        $holidays = Holiday::active()->where('date', '=', Carbon::now()->addDay())->get();
        dd($holidays);
    }
}
