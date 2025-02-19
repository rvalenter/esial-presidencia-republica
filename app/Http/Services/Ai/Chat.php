<?php

namespace App\Http\Services\Ai;

use GuzzleHttp\Client;

class Chat
{
    public static function engine(string $message): string
    {
        $client = new Client();

        $url = 'https://api.openai.com/v1/chat/completions';

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('OPENAI_API_KEY'),
        ];

        $body = [
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $message,
                ],
            ],
        ];

        $response = $client->post($url, [
            'headers' => $headers,
            'json' => $body,
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return $result['choices'][0]['message']['content'];
    }
}
