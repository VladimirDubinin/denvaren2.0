<?php

namespace App\TelegramBot\Domain\Exceptions;

final class AddDateException extends \Exception
{
    public static function earlyDate(): AddDateException
    {
        return new self('Введённая дата не должна быть раньше ' . date('d.m.Y'));
    }

    public static function incorrectDate(): AddDateException
    {
        return new self('Некорректная дата :( Введи дату напоминания в формате "дд.мм.гггг"');
    }
}
