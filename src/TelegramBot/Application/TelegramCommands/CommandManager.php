<?php

namespace App\TelegramBot\Application\TelegramCommands;

use App\TelegramBot\Domain\Exceptions\UnknownCommandException;
use App\TelegramBot\Infrastructure\TelegramCommands\TelegramCommandInterface;

final class CommandManager
{
    /**
     * @throws UnknownCommandException
     */
    public function getCommandInstance(string $command): TelegramCommandInterface
    {
        $commandClass = __NAMESPACE__ . '\\Commands\\' . $command . 'Command';
        if (!class_exists($commandClass)) {
            throw new UnknownCommandException("Нет такой команды: {$command} 🙃");
        }
        return new $commandClass;
    }
}
