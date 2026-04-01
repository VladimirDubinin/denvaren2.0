<?php

return [
  'bot_token' => env('TELEGRAM_BOT_TOKEN', ''),
  'secret_token' => env('SECRET_KEY', ''),
  'max_connections' => env('MAX_CONNECTIONS', 100),
];
