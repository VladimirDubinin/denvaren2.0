<?php

namespace App\TelegramBot\Domain\Exceptions;

final class KeyboardException extends \Exception
{
    public static function unknownMethod(string $name): KeyboardException
    {
        return new self("Неизвестный метод Keyboard [$name]");
    }
}
