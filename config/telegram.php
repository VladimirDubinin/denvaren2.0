<?php

return [
    'url' => env('APP_URL', '') . '/api/telegram/webhook',
    'bot_token' => env('TELEGRAM_BOT_TOKEN', ''),
    'secret_token' => env('SECRET_KEY', ''),
    'max_connections' => env('MAX_CONNECTIONS', 100),
    'openrouter_api_key' => env('OPENROUTER_API_KEY', ''),
    'openrouter_model' => env('OPENROUTER_MODEL', 'openrouter/auto'),
    'admin_id' => env('ADMIN_CHAT_ID', 0),

    'bot_commands' => [
        [
            'command' => 'list',
            'description' => 'Список дат'
        ],
        [
            'command' => 'add',
            'description' => 'Добавить дату'
        ],
        [
            'command' => 'delete',
            'description' => 'Удалить дату'
        ]
    ]
];

