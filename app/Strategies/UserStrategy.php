<?php
namespace App\Strategies;

use App\Models\User;

interface UserStrategy
{
    public function login(array $credentials): array;
    
    public function loginViaIntegration(array $credentials): array;

    public function logout(): mixed;

    public function register(array $credentials, bool $viaIntegration): User;
}
