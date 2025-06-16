<?php
namespace App\Services\Payment;

use App\Models\Payment\Payment;
use App\Services\BaseService;

class PaymentService extends BaseService
{

    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'user_id',
        'email',
        'name',
        'surname',
        'sender_bank',
        'buyer_bank',
        'payment_date',
        'price',
        'description',
        'completed'
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
        return Payment::class;
    }
}
