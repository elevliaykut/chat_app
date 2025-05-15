<?php

namespace App\Http\Controllers\Notification;

use App\API;
use App\Http\Controllers\Controller;
use App\Http\Resources\Notification\NotificationResource;
use App\Models\Notification\Notification;
use App\Services\Notification\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    protected int $defaultPerPage = 20;

    /**
     * NotificationController constructor.
     * @param NotificationService $notificationService
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $notifications = QueryBuilder::for(Notification::class)
            ->allowedFilters([
                AllowedFilter::exact('is_that_read')
            ])
            ->where('user_id', auth()->user()->id)
            ->defaultSort('-created_at')
            ->paginate($this->defaultPerPage);

        return API::success()->response(NotificationResource::collection($notifications));
    }

    /**
     * @return JsonResponse
     */
    public function readAll(): JsonResponse
    {
        Notification::where('is_that_read', false)
            ->where('user_id', auth()->user()->id)
            ->update(['is_that_read' => true]);
    
        return API::success()->response();
    }

    /**
     * @param int $notifId
     * @return JsonResponse
     */
    public function delete(int $notifId)
    {
        $notification = $this->notificationService->retrieveById($notifId);

        $notification->delete();

        return API::success()->response();
    }
}
