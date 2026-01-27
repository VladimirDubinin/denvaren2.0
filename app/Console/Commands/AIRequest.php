<?php

namespace App\Console\Commands;

use App\Http\Services\ChatService;
use Illuminate\Console\Command;

class AIRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:request {input : Your request}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправляет запрос к нейросети';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $request = $this->argument('input');
        $response = ChatService::requestAI($request);
        $this->info($response);
    }
}
