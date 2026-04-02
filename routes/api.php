<?php

use Illuminate\Support\Facades\Route;
use App\TelegramBot\Application\Controllers\WebhookController;

Route::post('/telegram/webhook', WebhookController::class);
