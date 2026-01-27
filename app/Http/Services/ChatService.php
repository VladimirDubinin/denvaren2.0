<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;

class ChatService
{
    public static function requestAI(string $input)
    {
        $apiKey = env('OPENROUTER_API_KEY');
        $headers = [
            "Authorization: Bearer {$apiKey}",
            "Content-Type: application/json",
        ];
        $body = json_encode([
            "model" => env('OPENROUTER_MODEL'),
            "messages" => [
                [
                    'role' => 'system',
                    'content' => 'You are a Telegram bot that sends holiday reminders and offers the user a greeting option.'
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
        if (array_key_exists('message', $response)) {
            return $response['message']['content'];
        } else {
            Log::debug('[' . date('d.m.Y H:i:s') . ']' . print_r($response, true));
            return null;
        }

    }
}
