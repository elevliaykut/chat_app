<?php

namespace App\Http\Controllers\User;

use App\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UploadUserProfilePhotoRequest;
use App\Http\Requests\User\UserChangePasswordRequest;
use App\Http\Requests\User\UserPersonalInformationRequest;
use App\Http\Requests\User\UserPersonalInformationUpdateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Services\User\UserDetailService;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    protected UserService $userService;

    protected UserDetailService $userDetailService;


    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService, UserDetailService $userDetailService)
    {
        $this->userService          = $userService;
        $this->userDetailService    = $userDetailService;
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
     * @param UserPersonalInformationUpdateRequest $userPersonalInformationUpdateRequest
     * @return JsonResponse
     */
    public function personalInformation(UserPersonalInformationUpdateRequest $userPersonalInformationUpdateRequest): JsonResponse
    {
        $user = $this->userService->retrieveById(auth()->user()->id);

        $validatedData = $userPersonalInformationUpdateRequest->validated();

        $validatedData['user_id'] = auth()->user()->id;

        $this->userDetailService->updateOrCreate($validatedData);

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

    /**
     * @param UploadUserProfilePhotoRequest $uploadUserProfilePhotoRequest
     * @return JsonResponse
     */
    public function uploadProfilePhoto(UploadUserProfilePhotoRequest $uploadUserProfilePhotoRequest): JsonResponse
    {
        $file = $uploadUserProfilePhotoRequest->file('photo');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('uploads', $fileName, 'public');
        $imageUrl = asset('storage/uploads/' . $fileName);

        $data = [
            'profile_photo_path'        => $imageUrl
        ];

        $user = $this->userService->update($data, auth()->user()->id);
        return API::success()->response(UserResource::make($user));
    }
}
