<?php

namespace App\Http\Controllers;

use App\Http\Services\AddHolidayService;
use App\Http\Services\DeleteHolidayService;
use App\Models\Holiday;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use Illuminate\Support\Stringable;

class DenvarenHandler extends WebhookHandler
{
    public function __construct(
        private readonly AddHolidayService $addHolidayService,
        private readonly DeleteHolidayService $deleteHolidayService
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
        if ($this->chat->waiting_add_answer) {
            $this->addHolidayService->updateNewHoliday($this->chat, $text);
        } elseif ($this->chat->waiting_delete_answer) {
            $this->deleteHolidayService->deleteHoliday($this->chat, $text);
        } else {
            $this->chat->html('"' . $text . '" - это то, что я так хотел услышать!')->send();
        }
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
        $holidays = Holiday::query()->where("chat_id", $this->chat->id)->get();
        if ($holidays->isEmpty()) {
            $this->reply('Не нашёл ни одной даты. Для добавления праздника используйте команду /add');
        } else {
            $html = 'Вот ваш список важных дат: <br><br>';
            foreach ($holidays as $holiday) {
                $html .= $holiday->date . ' - ' . $holiday->description . '<br>';
            }
            $this->chat->html($html)->send();
        }
    }

    public function add(): void
    {
        $this->chat->update(['waiting_add_answer' => true, 'waiting_delete_answer' => false]);
        $this->addHolidayService->addNewHoliday($this->chat);
    }

    public function delete(): void
    {
        $this->chat->update(['waiting_delete_answer' => true, 'waiting_add_answer' => false]);
        $this->reply('Укажите дату праздника, который хотите удалить');
    }
}
