<?php
namespace App\Services\Match;

use App\Models\Match\MatchHistory;
use App\Services\BaseService;

class UserService extends BaseService
{

    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'shown_user_id',
        'activity_user_id'
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
        return MatchHistory::class;
    }
}
