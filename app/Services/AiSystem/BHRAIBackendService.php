<?php

namespace App\Services\AiSystem;

use App\Exceptions\ClientErrorException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BHRAIBackendService
{
    public static function prompt(array $requestData, string $path)
    {
        try{
            $baseUrl = config('app.backend_ai_base_url');
            $client = Http::contentType('application/json')
                ->post("{$baseUrl}/generate/{$path}/", $requestData);
            return $client->json();

        }catch (\Exception $exception) {
            Log::error($exception);
        }

        throw new ClientErrorException("Unable to process request!");

    }
}
