<?php

namespace App\TelegramBot\Application\Services;

use App\TelegramBot\Infrastructure\Facades\Telegram;

final readonly class DeepSeekService
{
    public function requestAI(string $input)
    {
        $apiKey = config('telegram.openrouter_api_key');
        $headers = [
            "Authorization: Bearer {$apiKey}",
            "Content-Type: application/json",
        ];
        $body = json_encode([
            "model" => config('telegram.openrouter_model'),
            "messages" => [
                [
                    'role' => 'system',
                    'content' => 'Ты телеграм бот, который предлагает поздравление на основе описания события на русском языке.'
                ],
                [
                    'role' => 'user',
                    'content' => $input
                ]
            ],
        ]);

        $ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result, true);

        try {
            return $response['choices'][0]['message']['content'];
        } catch (\Exception $e) {
            Telegram::sendMessage($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }

        return null;
    }
}
