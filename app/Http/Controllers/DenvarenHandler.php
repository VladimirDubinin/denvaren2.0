<?php

namespace App\Http\Controllers;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use Illuminate\Support\Stringable;

class DenvarenHandler extends WebhookHandler
{
    protected function handleUnknownCommand($text): void
    {
        $this->chat->html('<b>Нет такой команды:</b> ' . $text)->send();
    }

    public function start(): void
    {
        $this->reply('Приветствую нового пользователя!');
    }

    protected function handleChatMessage(Stringable $text): void
    {
        $this->chat->html('"' . $text . '" - это то, что я так хотел услышать!')->send();
    }
}
