<?php

declare(strict_types=1);

namespace App\TelegramBot\Application\TelegramCommands;

use App\TelegramBot\Domain\Exceptions\UnknownCommandException;
use App\TelegramBot\Infrastructure\Telegram\Commands\TelegramCommandInterface;
use Illuminate\Support\Facades\App;

final class CommandManager
{
    /**
     * @throws UnknownCommandException
     */
    public function getCommandInstance(string $command): TelegramCommandInterface
    {
        $commandClass = __NAMESPACE__ . '\\Commands\\' . $command . 'Command';
        if (!class_exists($commandClass)) {
            throw UnknownCommandException::unknownCommand($command);
        }
        return app($commandClass);
    }
}
