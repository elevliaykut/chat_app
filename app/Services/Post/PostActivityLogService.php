<?php
namespace App\Services\Post;

use App\Models\Post\PostActivityLog;
use App\Services\BaseService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PostActivityLogService extends BaseService
{

    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'post_id',
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
        return PostActivityLog::class;
    }

    public function check(int $postId, int $activityUserId, int $activityType)
    {
        return PostActivityLog::where('post_id', $postId)
            ->where('activity_user_id', $activityUserId)
            ->where('activity_type', $activityType)
            ->first();
    }

    public function getByActivityUserAndType(int $activityUserId, int $activityType)
    {
        return PostActivityLog::where('activity_user_id', $activityUserId)
            ->where('activity_type', $activityType)
            ->get();
    }
}
