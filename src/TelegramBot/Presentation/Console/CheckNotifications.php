<?php

namespace App\TelegramBot\Presentation\Console;

use App\TelegramBot\Domain\Models\Holiday;
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
    public function handle(): void
    {
        $now = Carbon::now();
        // Отправляем простое уведомление в день события
        $this->info('Отправляю простые уведомления...');
        $holidays = Holiday::active()->where('date', '=', $now->format('Y-m-d'))->get();
        $holidays->map(function ($holiday) {
            $exitCode = $this->call('notification:simple', [
                'id' => $holiday->id,
            ]);
            if ($exitCode !== self::SUCCESS) {
                $this->error("Ошибка отправки простого уведомления по напоминанию {$holiday->id}, пропускаю");
            }
        });

        // Отправляем уведомление со сгенерированным поздравлением за день до события
        $this->info('Отправляю сгенерированные уведомления...');
        $holidays = Holiday::active()->where('date', '=', $now->addDay()->format('Y-m-d'))->get();
        $holidays->map(function ($holiday) {
            $exitCode = $this->call('notification:gen', [
                'id' => $holiday->id,
            ]);
            if ($exitCode !== self::SUCCESS) {
                $this->error("Ошибка отправки сгенерированного празднования по напоминанию {$holiday->id}, пропускаю");
            }
        });

        $this->info('Всё!');
    }
}
