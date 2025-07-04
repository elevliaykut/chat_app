<?php

namespace App\Http\Controllers\User;

use App\API;
use App\Helper\Statuses\UserStatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StorePhotoRequest;
use App\Http\Requests\User\UploadUserProfilePhotoRequest;
use App\Http\Requests\User\UserCaracteristicFeatureRequest;
use App\Http\Requests\User\UserChangePasswordRequest;
use App\Http\Requests\User\UserPersonalInformationUpdateRequest;
use App\Http\Requests\User\UserReportRequest;
use App\Http\Requests\User\UserSpouseCandidateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\Post\PostListResource;
use App\Http\Resources\User\UserMeResource;
use App\Http\Resources\User\UserPhotoResource;
use App\Http\Resources\User\UserProfileVisitResource;
use App\Http\Resources\User\UserResource;
use App\Models\Post\Post;
use App\Models\User\UserPhoto;
use App\Services\Notification\NotificationService;
use App\Services\User\UserCaracteristicService;
use App\Services\User\UserDetailService;
use App\Services\User\UserPhotoService;
use App\Services\User\UserProfileVisitLogService;
use App\Services\User\UserReportService;
use App\Services\User\UserService;
use App\Services\User\UserSpouseCandidateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    protected UserService $userService;

    protected UserSpouseCandidateService $userSpouseCandidateService;

    protected UserDetailService $userDetailService;

    protected UserPhotoService $userPhotoService;

    protected UserReportService $userReportService;

    protected UserCaracteristicService $userCaracteristicService;

    protected UserProfileVisitLogService $userProfileVisitLogService;

    protected NotificationService $notificationService;

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
        UserReportService $userReportService,
        UserCaracteristicService $userCaracteristicService,
        UserProfileVisitLogService $userProfileVisitLogService,
        NotificationService $notificationService
    )
    {
        $this->userService                  = $userService;
        $this->userDetailService            = $userDetailService;
        $this->userPhotoService             = $userPhotoService;
        $this->userSpouseCandidateService   = $userSpouseCandidateService;
        $this->userReportService            = $userReportService;
        $this->userCaracteristicService     = $userCaracteristicService;
        $this->userProfileVisitLogService   = $userProfileVisitLogService;
        $this->notificationService          = $notificationService;
    }

    /**
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        $user = $this->userService->retrieveById(auth()->user()->id);

        return API::success()->response(UserMeResource::make($user));
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
        
        $updateData = ['personal_info_complete' => true];

        // Sadece gelen ve tanımlı olan alanları ekle
        foreach (['name', 'surname', 'age', 'phone'] as $field) {
            if (isset($validatedData[$field])) {
                $updateData[$field] = $validatedData[$field];
            }
        }

        $user->update($updateData);

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

    public function caracteristicFeature(UserCaracteristicFeatureRequest $userCaracteristicFeatureRequest)
    {
        $user = $this->userService->retrieveById(auth()->user()->id);

        $validatedData = $userCaracteristicFeatureRequest->validated();

        $validatedData['user_id'] = $user->id;

        $this->userCaracteristicService->updateOrCreate($validatedData);

        return API::success()->response(UserResource::make($user));
    }

    /**
     * @param StorePhotoRequest $storePhotoRequest
     * @return JsonResponse
     */
    public function storePhoto(StorePhotoRequest $storePhotoRequest): JsonResponse
    {
        $userId = auth()->user()->id;
        
        $existingPhotoCount = $this->userPhotoService->countByUserId($userId);
    
        if ($existingPhotoCount >= 7) {
            return API::error()->errorMessage("En fazla 7 fotoğraf yükleyebilirsiniz.")->response();
        }

        $file = $storePhotoRequest->file('photo');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('photo', $fileName, 'public');
        $imageUrl = asset('storage/photo/' . $fileName);

        $data = [
            'user_id'           => auth()->user()->id,
            'photo_path'        => $imageUrl
        ];

        $userPhoto = $this->userPhotoService->create($data);

        return API::success()->response(UserPhotoResource::make($userPhoto));
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function listPhoto(int $userId): JsonResponse
    {
        $currentUserId = auth()->id();
        $user = $this->userService->retrieveById($userId);

        $photos = $user->photos;

        if ($userId !== $currentUserId) {
            $photos = $photos->where('status', 1);
        }

        return API::success()->response(UserPhotoResource::collection($photos));
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
            'profile_photo_path'        => $imageUrl,
            'photo_approve'             => 0,
        ];

        $user = $this->userService->update($data, auth()->user()->id);
        return API::success()->response(UserResource::make($user));
    }
    
    /**
     * @return JsonResponse
     */
    public function photos(): JsonResponse
    {
        $user = auth()->user();

        $oppositeGender = $user->gender === 0 ? 1 : 0;

        $photos = UserPhoto::where('status', 1)
            ->where('user_id', '!=', $user->id)
            ->whereHas('user', function ($query) use ($oppositeGender) {
                $query->where('gender', $oppositeGender);
            })
            ->get();

        return API::success()->response(UserPhotoResource::collection($photos));
    }

    /**
     * @return JsonResponse
     */
    public function myPosts(): JsonResponse
    {
        $user = auth()->user();

        $posts = QueryBuilder::for(Post::class)
            ->where('creator_user_id', $user->id)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('creator_user_id'),
                AllowedFilter::exact('status')
            ])
            ->defaultSort('-created_at')
            ->get();

        return API::success()->response(PostListResource::collection($posts));
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
    public function blockedUser(Request $request, int $userId)
    {
        $user = $this->userService->retrieveById(auth()->user()->id);
        
        if($request->input('status') == 1) {
            if($user->id == $userId) {
                return API::error()->errorMessage('Kendinizi Engelleyemezsiniz!')->response();
            }
            if($user->hasBlocked($userId)) {
                return API::error()->errorMessage('Zaten Bu Kullanıcıyı Engellediniz!')->response();
            }
            $user->blockedUsers()->attach($userId);
            return API::success()->message('Kullanıcı Başarılı Bir Şekilde Engellendi!')->response();
        }

        if($request->input('status') == 0) {
            if($user->id == $userId) {
                return API::error()->errorMessage('Kendinizi Engelleyemezsiniz!')->response();
            }
            $user->blockedUsers()->detach($userId);
            return API::success()->message('Kullanıcının Başarılı Bir Şekilde Engeli Kaldırıldı!')->response();
        }
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

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function createUserProfileVisitLog(int $userId): JsonResponse
    {
        $this->userService->retrieveById($userId);

        if($this->userProfileVisitLogService->check($userId)) {
            return API::error()->errorMessage('Zaten Log Edilmiş!')->response();
        }

        $data = [
            'user_id'           => $userId,
            'activity_user_id'  => auth()->user()->id
        ];

        $this->userProfileVisitLogService->create($data);

        $notifData = [
            'user_id'               => $userId,
            'notified_user_id'      => auth()->user()->id,
            'message'               => 'Profilinizi Ziyaret Etti'
        ];

        $this->notificationService->create($notifData);

        return API::success()->response();
    }

    /**
     * @return JsonResponse
     */
    public function getUserProfileVisit(): JsonResponse
    {
        $user = auth()->user();

        $visits = $user->profileVisitLogs()
            ->whereHas('user', function ($query) {
                $query->where('status', UserStatusHelper::USER_STATUS_ACTIVE)
                    ->where('type', 1)
                    ->whereDoesntHave('blockers', function ($subQuery) {
                    $subQuery->where('blocker_id', auth()->id());
                });
            })
            ->get();

        return API::success()->response(UserProfileVisitResource::collection($visits));
    }
}
