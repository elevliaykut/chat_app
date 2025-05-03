<?php
namespace App\Services\User;

use App\Models\User\UserCaracteristicFeature;
use App\Services\BaseService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserCaracteristicService extends BaseService
{

    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'user_id',
        'question_one',
        'question_two',
        'question_three',
        'question_four',
        'question_five',
        'question_six',
        'question_seven',
        'question_eight',
        'question_nine',
        'question_ten',
        'question_eleven',
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
        return UserCaracteristicFeature::class;
    }

    public function updateOrCreate(array $data)
    {
        return UserCaracteristicFeature::updateOrCreate(
            ['user_id' => $data['user_id']], // Arama kriteri
            $data                            // Güncellenecek veya eklenecek veri
        );
    }
}
