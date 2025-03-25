<?php
namespace App\Services\User;

use App\Models\User\UserActivityLog;
use App\Services\BaseService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserActivityLogService extends BaseService
{

    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'user_id',
        'activity_user_id',
        'activity_type'
    ];

    /**
     * @return array|string[]
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return UserActivityLog::class;
    }

    public function check(int $userId, int $activityUserId, int $activityType)
    {
        return UserActivityLog::where('user_id', $userId)
            ->where('activity_user_id', $activityUserId)
            ->where('activity_type', $activityType)
            ->first();
    }

    public function getByActivityUserAndType(int $activityUserId, int $activityType)
    {
        return UserActivityLog::where('activity_user_id', $activityUserId)
            ->where('activity_type', $activityType)
            ->get();
    }
}
