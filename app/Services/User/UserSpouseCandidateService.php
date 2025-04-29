<?php
namespace App\Services\User;

use App\Models\User\UserSpouseCandidate;
use App\Services\BaseService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserSpouseCandidateService extends BaseService
{

    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'user_id',
        'age_range',
        'marital_status',
        'have_a_child',
        'use_cigarette',
        'use_alcohol',
        'education_status',
        'salary',
        'not_compromise',
        'community',
        'sect',
        'do_you_pray',
        'physical_disability'
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
        return UserSpouseCandidate::class;
    }

    public function updateOrCreate(array $data)
    {
        return UserSpouseCandidate::updateOrCreate(
            ['user_id' => $data['user_id']], // Arama kriteri
            $data                            // Güncellenecek veya eklenecek veri
        );
    }
}
