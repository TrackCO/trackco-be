<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\GoogleVerificationRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Support\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ActivateAccountRequest;

class AuthenticationsController extends Controller
{
    private UserService $userService;
    public function __construct(UserService $userService){
        $this->userService = $userService;
        $this->resourceItem = UserResource::class;
    }

    public function login(LoginRequest $request)
    {
        $authData = $this->userService->login($request->validated());
        return $this->respondWithItem($authData['user'], [
            'message' => "Successfully logged in",
            'token' => $authData['token'],
            'expires_at' => $authData['expires_at']
        ]);
    }

    public function googleVerify(GoogleVerificationRequest $request)
    {
        $authData = $this->userService->googleLogin($request->validated());
        return $this->respondWithItem($authData['user'], [
            'message' => "Successfully logged in",
            'token' => $authData['token'],
            'expires_at' => $authData['expires_at']
        ]);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $resetData = $this->userService->sendResetLink($request->validated());

        return $this->respondWithCustomData(['message' => "Password reset link sent successfully."]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        return $this->respondWithCustomData($this->userService->resetPassword($request->validated()));
    }

    public function activateAccount(ActivateAccountRequest $request)
    {
        $this->userService->activateAccount($request->validated());

        return $this->respondWithCustomData(['message' => 'Account activated successfully.']);
    }
}
