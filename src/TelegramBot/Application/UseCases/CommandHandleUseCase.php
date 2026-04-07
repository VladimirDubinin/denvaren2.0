<?php

declare(strict_types=1);

namespace App\TelegramBot\Application\UseCases;

use App\TelegramBot\Application\Request\DTO\TelegramRequestDTO;
use App\TelegramBot\Application\TelegramCommands\CommandManager;
use App\TelegramBot\Domain\Exceptions\UnknownCommandException;
use App\TelegramBot\Domain\Models\Chat;
use App\TelegramBot\Infrastructure\Facades\Telegram;

final readonly class CommandHandleUseCase
{
    public function __construct(
        private CommandManager $commandManager,
    ) {
    }

    public function execute(TelegramRequestDTO $DTO): void
    {
        $this->prepareCommandName($command);
        try {
            $this->commandManager->getCommandInstance($command)->handle($DTO);
        } catch (UnknownCommandException $e) {
            Telegram::sendMessage($e->getMessage(), $DTO->chat->telegram_id);
        }
    }

    private function prepareCommandName(string &$text): void
    {
        $text = preg_replace('/[^a-zA-Z0-9]/', '', $text);
        $text = ucfirst($text);
    }
}
