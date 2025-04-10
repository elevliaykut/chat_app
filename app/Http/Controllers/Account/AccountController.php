<?php

namespace App\Http\Controllers\Account;

use App\API;
use App\Helper\Statuses\UserStatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Services\User\UserService;
use Illuminate\Http\Request;

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
    public function freeze()
    {
        $data = [
            'status'        => UserStatusHelper::USER_STATUS_FREEZE
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
}
