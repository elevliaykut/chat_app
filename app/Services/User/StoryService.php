<?php
namespace App\Services\User;

use App\Models\User\Story;
use App\Services\BaseService;

class StoryService extends BaseService
{

    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'user_id',
        'media_path',
        'caption',
        'expires_at',
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
        return Story::class;
    }
}
