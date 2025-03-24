<?php

namespace App\Http\Controllers\User;

use App\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserChangePasswordRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    protected UserService $userService;

    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        $user = $this->userService->retrieveById(auth()->user()->id);
        return API::success()->response(UserResource::make($user));
    }

    /**
     * @param UserUpdateRequest $userUpdateRequest
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $userUpdateRequest): JsonResponse
    {
        $validatedData = $userUpdateRequest->validated();

        $user = $this->userService->update($validatedData, auth()->user()->id);

        return API::success()->response(UserResource::make($user));
    }

    /**
     * @param UserChangePasswordRequest $userChangePasswordRequest
     * @return JsonResponse
     */
    public function changePassword(UserChangePasswordRequest $userChangePasswordRequest): JsonResponse
    {
        $validatedData = $userChangePasswordRequest->validated();
        
        if($validatedData['password'] !== $validatedData['password_confirmation']) {
            return API::error()->errorMessage('Şifreler Eşleşmedi!')->response();
        }

        $this->userService->update($validatedData, auth()->user()->id);

        return API::success()->response();
    }
}
