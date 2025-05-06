<?php
namespace App\Services\User;

use App\Models\User\UserProfileVisitLog;
use App\Services\BaseService;

class UserProfileVisitLogService extends BaseService
{

    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'user_id',
        'activity_user_id',
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
        return UserProfileVisitLog::class;
    }

    public function check(int $userId)
    {
        return UserProfileVisitLog::where('user_id', $userId)
            ->where('user_id', $userId)
            ->where('activity_user_id', auth()->user()->id)
            ->first();
    }
}
