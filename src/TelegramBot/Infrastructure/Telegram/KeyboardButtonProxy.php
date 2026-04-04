<?php

declare(strict_types=1);

namespace App\TelegramBot\Infrastructure\Telegram;

use App\TelegramBot\Domain\Exceptions\KeyboardException;

/**
 * @internal
 *
 * @mixin Button
 */
class KeyboardButtonProxy extends Keyboard
{
    private Button $button;

    public function __construct(Keyboard $proxied, Button $button)
    {
        $this->button = $button;
        $this->buttons = $proxied->buttons;
    }

    /**
     * @param array<array-key, mixed> $arguments
     * @throws KeyboardException
     */
    public function __call(string $name, array $arguments): KeyboardButtonProxy
    {
        if (!method_exists($this->button, $name)) {
            throw KeyboardException::unknownMethod($name);
        }

        $this->button->$name(...$arguments);

        return $this;
    }
}
