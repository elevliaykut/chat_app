<?php
namespace App\Services\User;

use App\Models\User\UserPhoto;
use App\Services\BaseService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserPhotoService extends BaseService
{

    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'user_id',
        'photo_path',
        'media_id',
        'status'
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
        return UserPhoto::class;
    }

    public function countByUserId(int $userId): int
    {
        return UserPhoto::where('user_id', $userId)->count();
    }
}
