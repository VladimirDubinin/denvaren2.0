<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Holiday;
use DefStudio\Telegraph\Facades\Telegraph;
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

    public function com(): void
    {
        Telegraph::registerBotCommands([
            'list' => 'Список дат',
            'add' => 'Добавить дату',
            'delete' => 'Удалить дату',
        ])->send();
    }

    public function list(): void
    {
        $this->reply('Вывожу список дат...');
    }

    public function add(): void
    {
        $chat = $this->chat;
        $holiday = new Holiday();
        $holiday->chat_id = $chat->id;
        $chat->waiting_add_answer = true;
        $chat->save();
        $holiday->save();
        $this->reply('Введите дату праздника');
    }

    public function delete(): void
    {
        $this->reply('Удаляю дату...');
    }
}
