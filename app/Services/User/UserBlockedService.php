<?php
namespace App\Services\User;

use App\Models\User\UserBlocked;
use App\Services\BaseService;


class UserBlockedService extends BaseService
{

    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'blocker_id',
        'blocked_id'
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
        return UserBlocked::class;
    }
}