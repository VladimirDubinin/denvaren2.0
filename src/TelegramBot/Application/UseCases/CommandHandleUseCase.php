<?php

namespace App\TelegramBot\Application\UseCases;

use App\TelegramBot\Application\TelegramCommands\CommandManager;
use App\TelegramBot\Domain\Exceptions\UnknownCommandException;
use App\TelegramBot\Infrastructure\Facades\Telegram;

final readonly class CommandHandleUseCase
{
    public function __construct(
        private CommandManager $commandManager,
    ) {
    }

    public function execute(int $chatId, string $command): void
    {
        $this->prepareCommandName($command);
        try {
            $this->commandManager->getCommandInstance($command)->handle($chatId);
        } catch (UnknownCommandException $e) {
            Telegram::sendMessage($e->getMessage(), $chatId);
        }
    }

    private function prepareCommandName(string &$text): void
    {
        $text = preg_replace('/[^a-zA-Z0-9]/', '', $text);
        $text = ucfirst($text);
    }
}
