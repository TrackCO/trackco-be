<?php

namespace App\Http\Controllers\Api\Auth;

use App\Exceptions\ClientErrorException;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Support\Traits\ResponseTrait;
use Illuminate\Http\Request;

class UserManagementsController extends Controller
{
    use ResponseTrait;
    private UserService $userService;
    public function __construct(UserService $userService){
        $this->userService = $userService;
        $this->resourceItem = UserResource::class;
    }

    /**
     * Update functionality
     * @param Request $request
     * @param $type
     * @return mixed
     * @throws ClientErrorException
     */
    public function update(Request $request, $type): mixed
    {
        $request->validate([
            'password' => 'nullable|string|min:8|confirmed',
            'contact_no' => 'nullable|string',
            'website' => 'nullable|string',
            'country' => 'nullable|exists:countries,id',
            'no_of_employees' => 'nullable|integer',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $intendedMethod = (self::determineUpdateMethodType($type));

        return $this->respondWithItem($this->userService->$intendedMethod($request), ['message' => 'Information has been updated successfully']);
    }

    /**
     * @param string $type
     * @return string
     * @throws ClientErrorException
     */
    private static function determineUpdateMethodType(string $type): string
    {
        switch ($type){
            case 'password':
                $intendedMethod = 'managePassword';
                break;
            case 'personalInfo':
            case 'contact':
                $intendedMethod = 'manageUserInfo';
                break;
            case 'picture':
                $intendedMethod = 'manageUserPictureUpdate';
                break;
            default:
                throw new ClientErrorException('The requested resource was not found.');
        }

        return $intendedMethod;
    }
}
