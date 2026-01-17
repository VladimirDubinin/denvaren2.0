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
            'list' => 'Список напоминаний',
            'add' => 'Добавить напоминание',
            'delete' => 'Удалить напоминание',
        ])->send();
    }

    public function list(): void
    {
        $holidays = Holiday::active()
            ->where("chat_id", $this->chat->id)
            ->get();
        if ($holidays->isEmpty()) {
            $this->reply('Не нашёл ни одного напоминания. Для добавления используйте команду /add');
        } else {
            $html = "Вот ваш список важных дат: \n\n";
            foreach ($holidays as $holiday) {
                $html .= $holiday->date->format('d.m.Y') . ' - ' . $holiday->description . "\n";
            }
            $this->chat->html($html)->send();
        }
    }

    public function add(): void
    {
        $this->addHolidayService->addNewHoliday($this->chat);
    }

    public function delete(): void
    {
        $this->deleteHolidayService->startDeletingHoliday($this->chat);
    }

    public function deleteById(): void
    {
        $holiday_id = $this->data->get('id');
        $result = $this->deleteHolidayService->deleteHolidayById($holiday_id, $this->chat->id);
        $result ?
            $this->chat->message('Напоминание удалено')->send() :
            $this->chat->message('Напоминание не найдено')->send();
    }
}
