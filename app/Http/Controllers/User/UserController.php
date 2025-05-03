<?php

namespace App\Http\Controllers\User;

use App\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StorePhotoRequest;
use App\Http\Requests\User\UploadUserProfilePhotoRequest;
use App\Http\Requests\User\UserChangePasswordRequest;
use App\Http\Requests\User\UserPersonalInformationUpdateRequest;
use App\Http\Requests\User\UserReportRequest;
use App\Http\Requests\User\UserSpouseCandidateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\Post\PostListResource;
use App\Http\Resources\User\UserPhotoResource;
use App\Http\Resources\User\UserResource;
use App\Services\User\UserDetailService;
use App\Services\User\UserPhotoService;
use App\Services\User\UserReportService;
use App\Services\User\UserService;
use App\Services\User\UserSpouseCandidateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $userService;

    protected UserSpouseCandidateService $userSpouseCandidateService;

    protected UserDetailService $userDetailService;

    protected UserPhotoService $userPhotoService;

    protected UserReportService $userReportService;

    /**
     * UserController constructor.
     * @param UserService $userService
     * @param UserDetailService $userDetailService
     * @param UserPhotoService $userPhotoService
     */
    public function __construct(
        UserService $userService, 
        UserDetailService $userDetailService, 
        UserPhotoService $userPhotoService, 
        UserSpouseCandidateService $userSpouseCandidateService,
        UserReportService $userReportService
    )
    {
        $this->userService                  = $userService;
        $this->userDetailService            = $userDetailService;
        $this->userPhotoService             = $userPhotoService;
        $this->userSpouseCandidateService   = $userSpouseCandidateService;
        $this->userReportService            = $userReportService;
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
     * @param UserSpouseCandidateRequest $userSpouseCandidateRequest
     * @return JsonResponse
     */
    public function spouseCandidate(UserSpouseCandidateRequest $userSpouseCandidateRequest)
    {
        $user = $this->userService->retrieveById(auth()->user()->id);

        $validatedData = $userSpouseCandidateRequest->validated();
        
        $validatedData['user_id'] = auth()->user()->id;

        $this->userSpouseCandidateService->updateOrCreate($validatedData);

        return API::success()->response(UserResource::make($user));
    }

    /**
     * @param StorePhotoRequest $storePhotoRequest
     * @return JsonResponse
     */
    public function storePhoto(StorePhotoRequest $storePhotoRequest): JsonResponse
    {
        $file = $storePhotoRequest->file('photo');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('uploads', $fileName, 'public');
        $imageUrl = asset('storage/uploads/' . $fileName);

        $data = [
            'user_id'           => auth()->user()->id,
            'photo_path'        => $imageUrl
        ];

        $userPhoto = $this->userPhotoService->create($data);

        return API::success()->response(UserPhotoResource::make($userPhoto));
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

    /**
     * @return JsonResponse
     */
    public function myPosts(): JsonResponse
    {
        $user = auth()->user();

        return API::success()->response(PostListResource::collection($user->posts));
    }

    /**
     * @return JsonResponse
     */
    public function myBlockedUsers(): JsonResponse
    {
        $user = $this->userService->retrieveById(auth()->user()->id);

        return API::success()->response(UserResource::collection($user->blockedUsers));
    }

    /**
     * @param Request $request
     * @param int $userId
     * @return JsonResponse
     */
    public function blockedUser(Request $request, int $userId): JsonResponse
    {
        $user = $this->userService->retrieveById(auth()->user()->id);

        if($user->id == $userId) {
            return API::error()->errorMessage('Kendinizi Engelleyemezsiniz!')->response();
        }

        if($user->hasBlocked($userId)) {
            return API::error()->errorMessage('Zaten Bu Kullanıcıyı Engellediniz!')->response();
        }

        $user->blockedUsers()->attach($userId);

        return API::success()->message('Kullanıcı Başarılı Bir Şekilde Engellendi!')->response();
    }

    /**
     * @param Request $request
     * @param int $userId
     * @return JsonResponse
     */
    public function unBlockedUser(Request $request, int $userId): JsonResponse
    {
        $user = $this->userService->retrieveById(auth()->user()->id);

        $user->blockedUsers()->detach($userId);

        return API::success()->message("Kullanıcının Engeli Kaldırıldı!")->response();
    }

    /**
     * @param UserReportRequest $userReportRequest
     * @param int $userId
     * @return JsonResponse
     */
    public function reportUser(UserReportRequest $userReportRequest, int $userId): JsonResponse
    {
        $validatedData = $userReportRequest->validated();
        
        $user = $this->userService->retrieveById($userId);
        
        $validatedData['user_id'] = $user->id;

        $validatedData['creator_user_id'] = auth()->user()->id;

        $this->userReportService->create($validatedData);

        return API::success()->response();
    }
}
