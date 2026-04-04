<?php

declare(strict_types=1);

namespace App\TelegramBot\Infrastructure\Telegram;

class Button
{
    private string $url;
    private array $callbackData = [];

    private function __construct(
        private readonly string $label,
    ) {
    }

    public static function make(string $label): Button
    {
        return new self($label);
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

    private function param(string $key, int|string $value): static
    {
        $key = trim($key);
        $value = trim((string) $value);

        $this->callbackData[] = "$key:$value";
        return $this;
    }

    public function toArray(): array
    {
        $array = [
            'text' => $this->label,
        ];

        if (count($this->callbackData) > 0) {
            $array['callback_data'] = implode(';', $this->callbackData);
        }

        if (isset($this->url)) {
            $array['url'] = $this->url;
        }

        return $array;
    }
}
