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
        $keyboard = [];

        $row = [];
        $rowWidth = 0;

        foreach ($this->buttons as $button) {
            if ($rowWidth + $button->getWidth() > 1) {
                $keyboard[] = $row;
                $row = [];
                $rowWidth = 0;
            }

            $row[] = $button->toArray();
            $rowWidth += $button->getWidth();
        }

        $keyboard[] = $row;

        return [
            'inline_keyboard' => $keyboard
        ];
    }
}
