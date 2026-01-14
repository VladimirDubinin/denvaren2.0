<?php

namespace App\Http\Controllers;

use DefStudio\Telegraph\Handlers\WebhookHandler;

class DenvarenHandler extends WebhookHandler
{
    protected function handleUnknownCommand($text): void
    {
        $this->chat->html('<b>Нет такой команды:</b> ' . $text)->send();
    }

    public function start(): void
    {
        $this->reply('Приветвую нового пользователя!');
    }
}
