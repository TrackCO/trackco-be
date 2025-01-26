<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewAccountRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Support\Traits\ResponseTrait;

class RegistrationsController extends Controller
{
    private UserService $userService;
    public function __construct(UserService $userService){
        $this->userService = $userService;
        $this->resourceItem = UserResource::class;
    }

    public function __invoke(NewAccountRequest $request)
    {
        $registeredUser = $this->userService->register($request->validated());
        return $this->respondWithItem($registeredUser, [
            'message' => 'Account has been created successfully.'
        ]);
    }
}
