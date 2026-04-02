<?php

namespace App\TelegramBot\Application\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Stringable;
use App\Application\Controllers\Keyboard;
use App\Application\Controllers\Telegraph;
use App\TelegramBot\Domain\Models\Holiday;
use App\TelegramBot\Infrastructure\Repositories\HolidayRepository;

class DenvarenHandler extends Controller
{
    public function __construct(
        private readonly HolidayRepository $holidayService,
    ) {
    }
    protected function handleUnknownCommand($text): void
    {
        $this->chat->html('<b>Нет такой команды:</b> ' . $text . ' 🙃')->send();
    }

    public function start(): void
    {
        $this->chat->message('Вот, что я умею:')->keyboard(Keyboard::make()
            ->button('Добавить напоминание')->action('add')
            ->button('Удалить напоминание')->action('delete')
            ->button('Список напоминаний')->action('list')
        )->send();
    }

    protected function handleChatMessage(Stringable $text): void
    {
        $chat = $this->chat;
        if ($chat->waiting_add_answer) {
            $this->holidayService->updateNewHoliday($chat, $text);
        } elseif ($chat->waiting_delete_answer) {
            $this->holidayService->deleteHoliday($chat, $text);
        } else {
            $chat->html("Извини, у меня нет времени на поболтать😎 Воспользуйся одной из команд")->send();
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
            $this->reply('Не нашёл ни одного напоминания🤷‍♂️ Для добавления используй команду /add');
        } else {
            $html = "Вот ваш список важных дат🤩 \n\n";
            foreach ($holidays as $holiday) {
                $html .= $holiday->date->format('d.m.Y') . ' - ' . $holiday->description . "\n";
            }
            $this->chat->html($html)->send();
        }
    }

    public function add(): void
    {
        $this->holidayService->addNewHoliday($this->chat);
    }

    public function delete(): void
    {
        $this->holidayService->startDeletingHoliday($this->chat);
    }

    public function deleteById(): void
    {
        $holiday_id = $this->data->get('id');
        $result = $this->holidayService->deleteHolidayById($holiday_id, $this->chat->id);
        $result ?
            $this->chat->message('Напоминание удалено🥲')->send() :
            $this->chat->message('Напоминание не найдено😢')->send();
    }

    public function stopDeleting(): void
    {
        if ($this->chat->waiting_delete_answer) {
            $this->chat->message('Напоминание остаётся в силе💪')->send();
        }
    }

    public function setRepeating(): void
    {
        $holiday_id = $this->data->get('id');
        $repeating = $this->data->get('repeat');
        $this->holidayService->setHolidayRepeating($this->chat->id, $holiday_id, $repeating);
        Telegraph::deleteKeyboard(messageId: $this->messageId)->send();
        $this->chat->message('Я всё записал, ожидай напоминание 😉')->send();
    }
}
