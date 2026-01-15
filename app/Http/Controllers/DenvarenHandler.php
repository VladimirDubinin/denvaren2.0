<?php

namespace App\Http\Controllers;

use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use Illuminate\Support\Stringable;

class DenvarenHandler extends WebhookHandler
{
    protected function handleUnknownCommand($text): void
    {
        $this->chat->html('<b>Нет такой команды:</b> ' . $text)->send();
    }

    protected function start(): void
    {
        $this->reply('Приветствую нового пользователя!');
    }

    protected function handleChatMessage(Stringable $text): void
    {
        $this->chat->html('"' . $text . '" - это то, что я так хотел услышать!')->send();
    }

    protected function com(): void
    {
        Telegraph::registerBotCommands([
            'list' => 'Список дат',
            'add' => 'Добавить дату',
            'delete' => 'Удалить дату',
        ])->send();
    }

    protected function list(): void
    {
        $this->reply('Вывожу список дат...');
    }

    protected function add(): void
    {
        $this->reply('Добавляю дату...');
    }

    protected function delete(): void
    {
        $this->reply('Удаляю дату...');
    }
}
