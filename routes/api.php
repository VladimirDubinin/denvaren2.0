<?php

use Illuminate\Support\Facades\Route;
use Src\TelegramBot\Application\Controllers\WebhookController;

Route::post('/telegram/webhook', WebhookController::class);
