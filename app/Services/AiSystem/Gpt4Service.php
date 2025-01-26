<?php

namespace App\Services\AiSystem;

use Illuminate\Support\Facades\Http;

class Gpt4Service
{
    public static function prompt($prompt, $temperature = 0.4)
    {
        $client = Http::contentType('application/json')
            ->withToken(config('app.open_ai_api_key'))
            ->post(config('app.open_ai_api_url'), [
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 500,
                'temperature'=> $temperature
            ]);
        return  isset($client->json()["choices"]) ? $client->json()["choices"][0]["message"]["content"] : [];
    }
}
