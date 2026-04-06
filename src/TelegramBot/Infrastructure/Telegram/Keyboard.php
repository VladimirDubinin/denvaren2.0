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

    public function chunk(int $chunk): Keyboard
    {
        $buttonWidth = 1 / $chunk;

        foreach ($this->buttons as $button) {
            $button->width($buttonWidth);
        }

        return $this;
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
