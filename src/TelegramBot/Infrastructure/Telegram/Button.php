<?php

declare(strict_types=1);

namespace App\TelegramBot\Infrastructure\Telegram;

class Button
{
    private string $url;
    private array $callbackData = [];
    private int $width = 0;

    private function __construct(
        private readonly string $label,
    ) {
    }

    public static function make(string $label): Button
    {
        return new self($label);
    }

    public function width(float $percentage): Button
    {
        $width = (int) ($percentage * 100);

        if ($width > 100) {
            $width = 100;
        }

        $this->width = $width;

        return $this;
    }

    public function getWidth(): float
    {
        if ($this->width === 0) {
            return 1;
        }

        return $this->width / 100;
    }

    public function url(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function action(string $name): static
    {
        return $this->param('action', $name);
    }

    public function param(string $key, int|string|bool $value): static
    {
        $key = trim($key);
        $value = trim((string) $value);

        $this->callbackData[$key] = $value;
        return $this;
    }

    public function toArray(): array
    {
        if (count($this->callbackData) > 0) {
            return [
                'text' => $this->label,
                'callback_data' => json_encode($this->callbackData)
            ];
        }

        if (isset($this->url)) {
            return [
                'text' => $this->label,
                'url' => $this->url
            ];
        }

        return [
            'text' => $this->label,
        ];
    }
}
