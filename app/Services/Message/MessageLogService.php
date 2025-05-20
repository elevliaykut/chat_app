<?php
namespace App\Services\Message;

use App\Models\Message\MessageLog;
use App\Services\BaseService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MessageLogService extends BaseService
{
    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'sender_id',
        'receiver_id',
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
        return MessageLog::class;
    }

    public function logExists(int $senderId, int $receiverId)
    {
        return $this->model
        ->where('sender_id', $senderId)
        ->where('receiver_id', $receiverId)
        ->exists();
    }
}
