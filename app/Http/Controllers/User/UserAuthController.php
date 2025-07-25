<?php

namespace App\Http\Controllers\User;

use App\API;
use App\Helper\Statuses\UserStatusHelper;
use App\Helper\Types\UserTypeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserLoginRequest;
use App\Http\Requests\User\UserRegisterRequest;
use App\Http\Resources\User\UserLoginResource;
use App\Http\Resources\User\UserRegisterResource;
use App\Http\Resources\User\UserResource;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

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
        $validatedData['status'] = UserStatusHelper::USER_STATUS_ACTIVE;

        if($this->userService->emailExists($validatedData['email'])) {
            $errorMessage = __('Bu email daha önceden kullanılmış!');
            return API::error()->errorMessage($errorMessage)->response();
        }

        if($this->userService->usernameExists($validatedData['username'])) {
            $errorMessage = __('Bu kullanıcı adı daha önceden kullanılmış!');
            return API::error()->errorMessage($errorMessage)->response();
        }

        $user = $this->userService->create($validatedData);

        $updateData = [
            'user_id'               => $user->id,
        ];

        $user->detail()->create($updateData);

        return API::success()->response(UserRegisterResource::make($user));
    }

    /**
     * @param UserLoginRequest $userLoginRequest
     * @return JsonResponse
     */
    public function login(UserLoginRequest $userLoginRequest): JsonResponse
    {
        $validatedData = $userLoginRequest->validated();

        $types = [UserTypeHelper::USER_TYPE_ADMIN, UserTypeHelper::USER_TYPE_CLIENT];
        
        $user = $this->userService->getUserNameWithTypes($validatedData['username'], $types);

        if(!$user) {
            return API::error()->errorMessage('Kullanıcı Bulunamadı')->response();
        }

        if (!Hash::check($validatedData['password'], $user->password)) {
            $errorMessage = __('Mail adresi / şifre eşleşmedi. Eğer şifrenizi unuttuysanız Şifremi Unuttum bölümünden şifrenizi değiştirebilirsiniz.');
            return API::error(Response::HTTP_UNPROCESSABLE_ENTITY)->errorMessage($errorMessage)->response();
        }

        $token = $user->createToken('API')->plainTextToken;
        
        return API::success()
            ->additionalData(['token' => $token])    
            ->response(UserLoginResource::make($user));
    }
}
