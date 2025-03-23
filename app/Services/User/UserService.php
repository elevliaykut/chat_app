<?php
namespace App\Services\User;

use App\Models\User;
use App\Services\BaseService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserService extends BaseService
{

    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'name',
        'surname',
        'email',
        'password',
        'phone',
        'type',
        'status',
        'token',
        'tckn',
        'birth_date',
        'phone_verified_at',
        'created_at',
        'updated_at'
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
        return User::class;
    }

    /**
     * @param string $phone
     * @return Builder|Model
     */
    public function getUserByPhone(string $phone): Model|Builder
    {
        return $this->model->newQuery()->where('phone', $phone)->firstOrFail();
    }

    /**
     * @param string $email
     * @return Builder|Model
     * @throws Exception
     */
    public function getUserByEmail(string $email): Model|Builder
    {
        return $this->model->newQuery()->where('email', $email)->firstOrFail();
    }

    /**
     * @param string $email
     * @param array $type
     * @return Builder|Model
     */
    public function getEmailUserWithTypes(string $email, array $type): Model|Builder
    {
        return $this->model->newQuery()->where('email', $email)->whereIn('type', $type)->firstOrFail();
    }

    /**
     * @param string $phone
     * @return bool
     */
    public function phoneExists(string $phone): bool
    {
        return $this->model->newQuery()->where('phone', $phone)->exists();
    }

    /**
     * @param $email
     * @param $type
     * @return bool
     * @throws Exception
     */
    public function emailExists($email, $type): bool
    {
        return $this->model->newQuery()->where('email', $email)->where('type', $type)->exists();
    }

    /**
     * @param User $user
     * @param string $email
     * @return bool
     */
    public function emailIsUnique(User $user, string $email): bool
    {
        return $this->model->newQuery()
            ->where('email', $email)
            ->where('id', '!=', $user->id)
            ->exists();
    }
}
