<?php

namespace App\Http\Controllers\Activity;

use App\API;
use App\Helper\Types\UserActivityTypeHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Activity\ActivityUserResource;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Models\User\UserActivityLog;
use App\Services\Notification\NotificationService;
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

    protected NotificationService $notificationService;

    /**
     * ActivityController constructor.
     * @param UserActivityLogService $userActivityLogService
     */
    public function __construct(UserActivityLogService $userActivityLogService, UserService $userService, NotificationService $notificationService)
    {
        $this->userActivityLogService = $userActivityLogService;
        $this->userService = $userService;
        $this->notificationService = $notificationService;
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function like(Request $request, int $userId)
    {
        $user = $this->userService->retrieveById($userId);

        if($request->input('status') == 1) {
            
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
    
            $notifData = [
                'user_id'               => $userId,
                'notified_user_id'      => auth()->user()->id,
                'message'               => 'Sizi Beğendi'
            ];
    
            $this->notificationService->create($notifData);
    
            return API::success()->response();
        }

        if($request->input('status') == 0) {

            $this->userService->unlikeUser($user);
            
            UserActivityLog::where('user_id', $userId)
                ->where('activity_user_id', auth()->user()->id)
                ->where('activity_type', UserActivityTypeHelper::USER_ACTIVITY_TYPE_LIKE)
                ->delete();

            return API::success()->response();
        }
        
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function favorite(Request $request, int $userId)
    {
        $user = $this->userService->retrieveById($userId);

        if($request->input('status') == 1) {
            
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
    
            $notifData = [
                'user_id'               => $userId,
                'notified_user_id'      => auth()->user()->id,
                'message'               => 'Sizi Favorilere Ekledi'
            ];
    
            $this->notificationService->create($notifData);
    
            return API::success()->response();
        }

        if($request->input('status') == 0) {
            
            $this->userService->unFavoriteUser($user);
            
            UserActivityLog::where('user_id', $userId)
                ->where('activity_user_id', auth()->user()->id)
                ->where('activity_type', UserActivityTypeHelper::USER_ACTIVITY_TYPE_MAKE_FAVORITE)
                ->delete();
            
            return API::success()->response();
        }
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function smile(Request $request, int $userId)
    {
        $user = $this->userService->retrieveById($userId);

        if($request->input('status') == 1) {
            
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
    
    
            $notifData = [
                'user_id'               => $userId,
                'notified_user_id'      => auth()->user()->id,
                'message'               => 'Size Gülümsedi'
            ];
    
            $this->notificationService->create($notifData);
    
            return API::success()->response();
        }

        if($request->input('status') == 0) {
            
            $this->userService->unSmileUser($user);

            UserActivityLog::where('user_id', $userId)
                ->where('activity_user_id', auth()->user()->id)
                ->where('activity_type', UserActivityTypeHelper::USER_ACTIVITY_TYPE_SMILE)
                ->delete();

            return API::success()->response();
        }
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
     * @return JsonResponse
     */
    public function filter(): JsonResponse
    {
        $currentUser = auth()->user();
        $oppositeGender = $currentUser->gender === 1 ? 0 : 1;

        $users = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('creator_user_id'),
                AllowedFilter::exact('status'),
                AllowedFilter::scope('near_users'),
                AllowedFilter::scope('born_today_date'),
                AllowedFilter::exact('gender'),
                AllowedFilter::scope('starts_between'),
                AllowedFilter::scope('username'),
                AllowedFilter::scope('min_age_range'),
                AllowedFilter::scope('max_age_range'),
                AllowedFilter::scope('min_tall'),
                AllowedFilter::scope('max_tall'),
                AllowedFilter::scope('min_weight'),
                AllowedFilter::scope('max_weight'),
                AllowedFilter::scope('city_id'),
                AllowedFilter::scope('job'),
                AllowedFilter::scope('marital_status'),
                AllowedFilter::scope('have_a_child'),
                AllowedFilter::scope('use_cigarette'),
                AllowedFilter::scope('use_alcohol'),
                AllowedFilter::scope('education'),
                AllowedFilter::scope('salary'),
                AllowedFilter::scope('physical'),
                AllowedFilter::scope('physical'),
                AllowedFilter::scope('has_photos'),
                AllowedFilter::scope('head_craft'),
            ])
            ->whereDoesntHave('blockers', function ($query) {
                $query->where('blocker_id', auth()->id());
            })
            ->defaultSort('-created_at')
            ->where('id', '!=', auth()->id()) // Burada kendi kullanıcıyı dışladık
            ->where('gender', $oppositeGender)
            ->paginate($this->defaultPerPage);

        return API::success()->response(UserResource::collection($users));
    }

    /**
     * Undocumented function
     *
     * @return JsonResponse
     */
    public function getOnlineUsers(): JsonResponse
    {
        $currentUser = auth()->user();
        $oppositeGender = $currentUser->gender === 1 ? 0 : 1;

        // Karşı cinsiyetteki kullanıcıların ID'lerini al
        $allUserIds = User::where('gender', $oppositeGender)->pluck('id');

        $onlineUserIds = [];

        foreach ($allUserIds as $id) {
            if (Cache::has('user-is-online-' . $id)) {
                $onlineUserIds[] = $id;
            }
        }

        // Online ve karşı cinsiyetteki, aynı zamanda seni engellemeyen kullanıcıları getir
        $users = User::whereIn('id', $onlineUserIds)
            ->get()
            ->filter(function ($user) use ($currentUser) {
                return !$user->isBlockedBy($currentUser->id);
            })
            ->values(); // ->values() ile filtrelenen koleksiyonu sıfırdan indexle

        return API::success()->response(UserResource::collection($users));
    }
}
