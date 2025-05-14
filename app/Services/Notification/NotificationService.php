<?php
namespace App\Services\Notification;

use App\Models\Notification\Notification;
use App\Services\BaseService;

class NotificationService extends BaseService
{

    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'user_id',
        'notified_user_id',
        'message',
        'is_that_read'
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
        return Notification::class;
    }
}
