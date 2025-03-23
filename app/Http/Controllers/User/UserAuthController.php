<?php

namespace App\Http\Controllers\User;

use App\API;
use App\Helper\Statuses\UserStatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRegisterRequest;
use App\Http\Resources\User\UserRegisterResource;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    
    protected UserService $userService;

    /**
     * UserAuthController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {   
        $this->userService = $userService;
    }

    /**
     * @param UserRegisterRequest $userRegisterRequest
     * @return JsonResponse
     */
    public function register(UserRegisterRequest $userRegisterRequest): JsonResponse
    {
        $validatedData = $userRegisterRequest->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['status'] = UserStatusHelper::USER_STATUS_INACTIVE;

        if($this->userService->emailExists($validatedData['email'])) {
            $errorMessage = __('Bu email daha önceden kullanılmış!');
            return API::error()->errorMessage($errorMessage)->response();
        }

        $user = $this->userService->create($validatedData);

        return API::success()->response(UserRegisterResource::make($user));
    }
}
