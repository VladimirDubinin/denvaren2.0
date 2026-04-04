<?php

declare(strict_types=1);

namespace App\TelegramBot\Infrastructure\Telegram;

class Keyboard
{
    protected array $buttons = [];

    public static function make(): Keyboard
    {
        return new self();
    }

    public function button(string $label): KeyboardButtonProxy
    {
        $button = Button::make($label);

        $this->buttons[] = $button;

        return new KeyboardButtonProxy($this, $button);
    }

    public function toArray(): array
    {
        $array = [];
        foreach ($this->buttons as $button) {
            $array[] = $button->toArray();
        }

        return [
            'inline_keyboard' => [
                $array
            ]
        ];
    }
}
