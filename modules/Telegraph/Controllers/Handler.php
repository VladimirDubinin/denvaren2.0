<?php

namespace Modules\Telegraph\Controllers;

use DefStudio\Telegraph\Handlers\WebhookHandler;

class Handler extends WebhookHandler
{
    protected function handleUnknownCommand($text): void
    {
        $this->chat->html('<b>Нет такой команды:</b> ' . $text)->send();
    }

    public function hello(): void
    {
        $this->reply('Привет!');
    }
}
