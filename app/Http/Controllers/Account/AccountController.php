<?php

namespace App\Http\Controllers\Account;

use App\API;
use App\Helper\Statuses\UserStatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserChangeEmailRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AccountController extends Controller
{
    /**
     * Undocumented variable
     *
     * @var UserService
     */
    protected UserService $userService;

    /**
     * Undocumented function
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function freeze(int $status)
    {
        $data = [
            'status'        => $status
        ];

        $user = $this->userService->update($data, auth()->user()->id);

        return API::success()->response(UserResource::make($user));
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function delete()
    {
        $user = auth()->user();

        $user->delete();

        return API::success()->message('Kullanıcı Başarılı Bir Şekilde Silindi!')->response();
    }

    /**
     * @param UserChangeEmailRequest $userChangeEmailRequest
     */
    public function changeEmail(UserChangeEmailRequest $userChangeEmailRequest): JsonResponse
    {
        $validatedData = $userChangeEmailRequest->validated();

        $currentUser = auth()->user();

        $currentUser->update([
            'email'     => $validatedData['email']
        ]);

        return API::success()->response(UserResource::make($currentUser));
    }
}
