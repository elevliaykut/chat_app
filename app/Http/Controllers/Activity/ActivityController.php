<?php

namespace App\Http\Controllers\Activity;

use App\API;
use App\Helper\Types\UserActivityTypeHelper;
use App\Http\Controllers\Controller;
use App\Services\User\UserActivityLogService;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
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
    public function like(int $userId)
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
}
