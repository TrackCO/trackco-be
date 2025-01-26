<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Http\Requests\SendReferralEmailRequest;
use Illuminate\Http\Request;
class ReferralController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Send a referral email.
     *
     * @param  SendReferralEmailRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendReferralEmail(SendReferralEmailRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->respondWithCustomData(
            $this->userService->sendReferralEmail($request->validated())
        );
    }

}
