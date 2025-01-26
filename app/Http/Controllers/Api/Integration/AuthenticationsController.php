<?php

namespace App\Http\Controllers\Api\Integration;

use App\Exceptions\ClientErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\IntegrationInitiationRequest;
use App\Http\Requests\IntegrationTokenGenerationRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthenticationsController extends Controller
{

    private UserService $userService;
    public function __construct(UserService $userService){
        $this->userService = $userService;
        $this->resourceItem = UserResource::class;
    }

    /**
     * @throws ClientErrorException
     */
    public function initiate(IntegrationInitiationRequest $request)
    {
        $registeredUser = $this->userService->register($request->validated(), viaIntegration: true);
        $token = Auth::guard('api')->claims(['exp' => strtotime('+30 days')])->login($registeredUser);

        if(!$token) throw new ClientErrorException('Unable to generate token.');
        $expiration = JWTAuth::setToken($token)->getPayload()->get('exp');
        $expirationTime = date('Y-m-d H:i:s', $expiration);
        return $this->respondWithItem($registeredUser, [
            'message' => 'Account has been created successfully.',
            'token' => $token,
            'expiration' => $expirationTime
        ]);
    }

    /**
     * @throws ClientErrorException
     */
    public function generateToken(IntegrationTokenGenerationRequest $request)
    {
        $authData = $this->userService->loginViaIntegration($request->validated());
        return $this->respondWithItem($authData['user'], [
            'message' => "Successfully logged in",
            'token' => $authData['token'],
            'expires_at' => $authData['expires_at']
        ]);
    }
}
