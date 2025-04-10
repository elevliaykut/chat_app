<?php
namespace App\Services\Message;

use App\Models\Message\Message;
use App\Services\BaseService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MessageService extends BaseService
{
    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'sender_id',
        'receiver_id',
        'message'
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
        return Message::class;
    }
}
