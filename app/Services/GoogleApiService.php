<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleApiService
{
    public static function verify(string $token)
    {
        return Http::contentType('application/json')
            ->get(config('services.google.verify'), [
                'access_token' => $token
            ])->json();
    }
}
