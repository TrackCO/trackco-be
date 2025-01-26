<?php

namespace App\Services\AiSystem;

use Illuminate\Support\Facades\Http;

class Gpt35Service
{
    public static function prompt($prompt, $temperature = 0.4)
    {
        $client = Http::contentType('application/json')
            ->withToken(config('app.open_ai_api_key'))
            ->post(config('app.open_ai_api_url'), [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 1000,
                'temperature'=> $temperature
            ]);

        return  isset($client->json()["choices"]) ? $client->json()["choices"][0]["message"]["content"] : [];
    }
}
