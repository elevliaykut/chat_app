<?php

namespace App\Http\Controllers\Activity;

use App\API;
use App\Helper\Types\UserActivityTypeHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Activity\ActivityUserResource;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\User\UserActivityLogService;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Cache;

class ActivityController extends Controller
{
    protected int $defaultPerPage = 20;

    protected UserActivityLogService $userActivityLogService;

    protected UserService $userService;

    /**
     * ActivityController constructor.
     * @param UserActivityLogService $userActivityLogService
     */
    public function __construct(UserActivityLogService $userActivityLogService, UserService $userService)
    {
        $this->userActivityLogService = $userActivityLogService;
        $this->userService = $userService;
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function like(int $userId): JsonResponse
    {
        $user = $this->userService->retrieveById($userId);

        if($this->userActivityLogService->check($userId, auth()->user()->id, UserActivityTypeHelper::USER_ACTIVITY_TYPE_LIKE)) {
            return API::error()->errorMessage('Beğendiğiniz bir profili tekrar beğenemezsiniz!')->response();
        }

        $this->userService->likeUser($user);

        $logData = [
            'user_id'           => $userId,
            'activity_user_id'  => auth()->user()->id,
            'activity_type'     => UserActivityTypeHelper::USER_ACTIVITY_TYPE_LIKE
        ];

        $this->userActivityLogService->create($logData);

        return API::success()->response();
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function favorite(int $userId): JsonResponse
    {
        $user = $this->userService->retrieveById($userId);

        if($this->userActivityLogService->check($userId, auth()->user()->id, UserActivityTypeHelper::USER_ACTIVITY_TYPE_MAKE_FAVORITE)) {
            return API::error()->errorMessage('Favorilediğiniz bir profili tekrar favorileyemezsiniz!')->response();
        }

        $this->userService->favoriteUser($user);

        $logData = [
            'user_id'           => $userId,
            'activity_user_id'  => auth()->user()->id,
            'activity_type'     => UserActivityTypeHelper::USER_ACTIVITY_TYPE_MAKE_FAVORITE
        ];

        $this->userActivityLogService->create($logData);

        return API::success()->response();
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function smile(int $userId): JsonResponse
    {
        $user = $this->userService->retrieveById($userId);

        if($this->userActivityLogService->check($userId, auth()->user()->id, UserActivityTypeHelper::USER_ACTIVITY_TYPE_SMILE)) {
            return API::error()->errorMessage('İfade bıraktığınız bir profile tekrar ifade bırakamazsınız!')->response();
        }

        $this->userService->smileUser($user);

        $logData = [
            'user_id'           => $userId,
            'activity_user_id'  => auth()->user()->id,
            'activity_type'     => UserActivityTypeHelper::USER_ACTIVITY_TYPE_SMILE
        ];

        $this->userActivityLogService->create($logData);

        return API::success()->response();
    }

    /**
     * @return JsonResponse
     */
    public function likedProfiles(): JsonResponse
    {
        $activityUserLog = $this->userActivityLogService->getByActivityUserAndType(auth()->user()->id, UserActivityTypeHelper::USER_ACTIVITY_TYPE_LIKE);

        return API::success()->response(ActivityUserResource::collection($activityUserLog));
    }

    /**
     * @return JsonResponse
     */
    public function favoriteProfiles(): JsonResponse
    {
        $activityUserLog = $this->userActivityLogService->getByActivityUserAndType(auth()->user()->id, UserActivityTypeHelper::USER_ACTIVITY_TYPE_MAKE_FAVORITE);

        return API::success()->response(ActivityUserResource::collection($activityUserLog));
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function similedProfiles()
    {
        $activityUserLog = $this->userActivityLogService->getByActivityUserAndType(auth()->user()->id, UserActivityTypeHelper::USER_ACTIVITY_TYPE_SMILE);
        
        return API::success()->response(ActivityUserResource::collection($activityUserLog));
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function filter()
    {
        $users = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('creator_user_id'),
                AllowedFilter::exact('status'),
                AllowedFilter::scope('near_users'),
                AllowedFilter::scope('born_today_date'),
                AllowedFilter::exact('gender'),
                AllowedFilter::scope('starts_between')
            ])
            ->defaultSort('-created_at')
            ->where('id', '!=', auth()->id()) // Burada kendi kullanıcıyı dışladık
            ->paginate($this->defaultPerPage);

        return API::success()->response(UserResource::collection($users));
    }

    public function getOnlineUsers()
    {
        // Online olan user id'lerini cache'den bul
        $allUserIds = User::pluck('id');
        $onlineUserIds = [];

        foreach ($allUserIds as $id) {
            if (Cache::has('user-is-online-' . $id)) {
                $onlineUserIds[] = $id;
            }
        }

        // Online userları çek ve döndür
        $users = User::whereIn('id', $onlineUserIds)->get();

        return API::success()->response(UserResource::collection($users));
    }
}
