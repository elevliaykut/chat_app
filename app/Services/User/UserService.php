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
        'username',
        'email',
        'password',
        'phone',
        'type',
        'status',
        'age',
        'token',
        'profile_photo_path',
        'tckn',
        'birth_date',
        'liked_by_me',
        'like_count',
        'favorite_count',
        'smile_count',
        'gender',
        'phone_verified_at',
        'photo_approve',
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
     * @param int $type
     * @return Builder|Model
     */
    public function getEmailUserWithTypes(string $email, int $type): Model|Builder
    {
        return $this->model->newQuery()->where('email', $email)->where('type', $type)->firstOrFail();
    }

    /**
     * @param string $userName
     * @param int $type
     * @return Builder|Model
     */
    public function getUserNameWithTypes(string $userName, array $types)
    {
        return $this->model->newQuery()->where('username', $userName)->whereIn('type', $types)->first();
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
     * @return bool
     * @throws Exception
     */
    public function emailExists($email): bool
    {
        return $this->model->newQuery()->where('email', $email)->exists();
    }

    /**
     * @param $username
     * @return bool
     * @throws Exception
     */
    public function usernameExists($username): bool
    {
        return $this->model->newQuery()->where('username', $username)->exists();
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

     /**
     * Belirtilen profilin beğeni sayısını artırır.
     *
     * @return User
     * @throws ModelNotFoundException
     */
    public function likeUser(User $user)
    {
        $user->increment('like_count');
        
        $user->update([
            'liked_by_me'   => true
        ]);
        
        return $user->fresh();
    }

    /**
     * Belirtilen profilin beğeni sayısını azaltır.
     *
     * @param User $user
     * @return User
     * @throws ModelNotFoundException
     */
    public function unlikeUser(User $user)
    {
        // like_count sıfırın altına düşmesin
        if ($user->like_count > 0) {
            $user->decrement('like_count');
        }

        $user->update([
            'liked_by_me' => false
        ]);

        return $user->fresh();
    }

    /**
     * Belirtilen favori beğeni sayısını artırır.
     *
     * @return User
     * @throws ModelNotFoundException
     */
    public function favoriteUser(User $user)
    {
        $user->increment('favorite_count');
        return $user->fresh();
    }

    /**
     * Belirtilen favori beğeni sayısını azaltır.
     *
     * @return User
     * @throws ModelNotFoundException
     */
    public function unFavoriteUser(User $user)
    {
        if($user->favorite_count > 0) {
            $user->decrement('favorite_count');
            return $user->fresh();
        }
    }

    /**
     * Belirtilen profilin beğeni sayısını artırır.
     *
     * @return User
     * @throws ModelNotFoundException
     */
    public function smileUser(User $user)
    {
        $user->increment('smile_count');
        return $user->fresh();
    }

    /**
     * Belirtilen profilin beğeni sayısını azaltır.
     *
     * @return User
     * @throws ModelNotFoundException
     */
    public function unSmileUser(User $user)
    {
        if($user->smile_count > 0) {
            $user->decrement('smile_count');
            return $user->fresh();
        }
    }
}
