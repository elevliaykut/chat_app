<?php

namespace App\Http\Controllers\Admin;

use App\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Http\Resources\Admin\AdminLoginResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserStoryResource;
use App\Models\User;
use App\Models\User\Story;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    protected UserService $userService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {   
        $this->userService = $userService;
    }

    /**
     * Undocumented function
     *
     * @param AdminLoginRequest $adminLoginRequest
     * @return JsonResponse
     */
    public function login(AdminLoginRequest $adminLoginRequest): JsonResponse
    {
        $validatedData = $adminLoginRequest->validated();

        $user = User::where('type', 2)->first();

        if (!Hash::check($validatedData['password'], $user->password)) {
            $errorMessage = __('Mail adresi / şifre eşleşmedi. Eğer şifrenizi unuttuysanız Şifremi Unuttum bölümünden şifrenizi değiştirebilirsiniz.');
            return API::error(Response::HTTP_UNPROCESSABLE_ENTITY)->errorMessage($errorMessage)->response();
        }

        $token = $user->createToken('API')->plainTextToken;

        return API::success()
            ->additionalData(['token'       => $token])
            ->response(AdminLoginResource::make($user));
    }

    /**
     *
     * @return JsonResponse
     */
    public function getUsers(): JsonResponse
    {
        $users = User::where('type', 1)->get();

        return API::success()->response(UserResource::collection($users));
    }

    /**
     * Undocumented function
     *
     * @param integer $userId
     * @return JsonResponse
     */
    public function deleteUser(int $userId): JsonResponse
    {
        $user = $this->userService->retrieveById($userId);

        $user->delete();

        return API::success()->response();
    }

    /**
     *
     * @return JsonResponse
     */
    public function stories(): JsonResponse
    {
        $stories = Story::where('status', 0)->get();

        return API::success()->response(UserStoryResource::collection($stories));
    }

    /**
     *
     * @param integer $storyId
     * @return JsonResponse
     */
    public function approveStory(int $storyId): JsonResponse
    {
        $story = Story::where('id', $storyId)->first();

        $story->update(['status' => 1]);

        return API::success()->response(UserStoryResource::make($story));
    }
}
