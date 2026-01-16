<?php

namespace App\Http\Controllers;

use App\Http\Services\AddHolidayService;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use Illuminate\Support\Stringable;

class DenvarenHandler extends WebhookHandler
{
    public function __construct(
        private readonly AddHolidayService $addHolidayService
    ) {
        parent::__construct();
    }
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
        $this->chat->html('"' . $text . '" - это то, что я так хотел услышать! ' . $this->chat->waiting_add_answer)->send();
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
        $this->addHolidayService->addNewHoliday($this->chat);
    }

    public function delete(): void
    {
        $this->reply('Удаляю дату...');
    }
}
