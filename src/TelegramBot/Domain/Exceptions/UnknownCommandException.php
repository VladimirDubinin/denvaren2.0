<?php

namespace App\TelegramBot\Domain\Exceptions;

final class UnknownCommandException extends \Exception
{
    public static function unknownCommand(string $command): UnknownCommandException
    {
        return new self("Нет такой команды: {$command} 🙃");
    }
}
