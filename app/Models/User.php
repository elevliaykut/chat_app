<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Match\MatchHistory;
use App\Models\Message\Message;
use App\Models\Notification\Notification;
use App\Models\Payment\Payment;
use App\Models\Post\Post;
use App\Models\Report\Report;
use App\Models\User\Story;
use App\Models\User\UserActivityLog;
use App\Models\User\UserBlocked;
use App\Models\User\UserCaracteristicFeature;
use App\Models\User\UserDetail;
use App\Models\User\UserPhoto;
use App\Models\User\UserProfileVisitLog;
use App\Models\User\UserSpouseCandidate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
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
        'personal_info_complete',
        'photo_approve'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'birth_date'        => 'datetime',
        'password' => 'hashed',
    ];

    public function stories()
    {
        return $this->hasMany(Story::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'user_id', 'id');
    }

    public function getIsOnlineAttribute()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    /**
     * @return HasOne
     */
    public function detail(): HasOne
    {
        return $this->hasOne(UserDetail::class, 'user_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function report(): HasOne
    {
        return $this->hasOne(Report::class, 'creator_user_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function spouseCandidate(): HasOne
    {
        return $this->hasOne(UserSpouseCandidate::class, 'user_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function caracteristicFeature(): HasOne
    {
        return $this->hasOne(UserCaracteristicFeature::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'creator_user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(UserActivityLog::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function profileVisitLogs(): HasMany
    {
        return $this->hasMany(UserProfileVisitLog::class, 'activity_user_id', 'id');
    }
    
    /**
     * @return HasMany
     */
    public function matchHistories(): HasMany
    {
        return $this->hasMany(MatchHistory::class, 'activity_user_id', 'id');
    }

    /**
     * int $userId
     * return bool
     */
    public function isLikedBy(int $userId): bool
    {
        return $this->activityLogs()
            ->where('activity_type',1)
            ->where('activity_user_id', $userId)
            ->exists();
    }

    /**
     * int $userId
     * return bool
     */
    public function isFavoritedBy(int $userId): bool
    {
        return $this->activityLogs()
            ->where('activity_type',2)
            ->where('activity_user_id', $userId)
            ->exists();
    }

        /**
     * int $userId
     * return bool
     */
    public function isSmiledBy(int $userId): bool
    {
        return $this->activityLogs()
            ->where('activity_type',3)
            ->where('activity_user_id', $userId)
            ->exists();
    }

    /**
     * @return HasMany
     */
    public function photos(): HasMany
    {
        return $this->hasMany(UserPhoto::class, 'user_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'receiver_id', 'id')->where('is_that_read',false);
    }
    
    public function blockedUsers()
    {
        return $this->belongsToMany(User::class, 'blocked_users', 'blocker_id', 'blocked_id');
    }
    
    public function blockers()
    {
        return $this->belongsToMany(User::class, 'blocked_users', 'blocked_id', 'blocker_id');
    }
    
    public function hasBlocked(int $userId)
    {
        return $this->blockedUsers()->where('blocked_id', $userId)->exists();
    }

    public function isBlockedBy(int $userId)
    {
        return $this->blockers()->where('blocker_id', $userId)->exists();
    }
    
    public function getAllMemberCountAttribute()
    {
        return self::count();
    }
    

    /********** Scope Functions ******/

    /**
     * Undocumented function
     *
     * @param [type] $query
     * @param [type] $type
     * @return void
     */
    public function scopeNearUsers($query, $type)
    {
        return $query->whereHas('detail', function($query) use ($type) {
            return $query->where('district_id', (int)$type);
        });
    }

    /**
     * Undocumented function
     *
     * @param [type] $query
     * @param [type] $date
     * @return void
     */
    public function scopeBornTodayDate($query, $date)
    {
        return $query->whereMonth('birth_date', Carbon::now()->month)
                 ->whereDay('birth_date', Carbon::now()->day);
    }

        /**
     * @param $query
     * @param $from
     * @param $to
     * @return mixed
     */
    public function scopeStartsBetween($query, $from, $to)
    {
        return $query->whereBetween('created_at', [Carbon::parse($from), Carbon::parse($to)]);
    }

    public function scopeUsername($query, $username)
    {
        return $query->where('username', 'ILIKE', "%{$username}%");
    }

    public function scopeMinAgeRange($query, $value)
    {
        $minAge = (int) $value; 

        return $query->where('age', '>=', $minAge);
    }

    public function scopeMaxAgeRange($query, $value)
    {
        $minAge = (int) $value; 

        return $query->where('age', '<=', $minAge);
    }

    public function scopeMinTall($query, $value)
    {
        $minTall = (float) $value;

        return $query->whereHas('detail', function($query) use ($minTall) {
            $query->where('tall', '>=', $minTall);
        });
    }

    public function scopeMaxTall($query, $value)
    {
        $maxTall = (float) $value;

        return $query->whereHas('detail', function($query) use ($maxTall) {
            $query->where('tall', '<=', $maxTall);
        });
    }

    public function scopeMinWeight($query, $value)
    {
        $minWeight = (int) $value;

        return $query->whereHas('detail', function($query) use ($minWeight) {
            $query->where('weight', '>=', $minWeight);
        });
    }

    public function scopeMaxWeight($query, $value)
    {
        $maxWeight = (int) $value;

        return $query->whereHas('detail', function($query) use ($maxWeight) {
            $query->where('weight', '<=', $maxWeight);
        });
    }

    public function scopeCityId($query, $value)
    {
        return $query->whereHas('detail', function($query) use ($value) {
            $query->where('city_id',  $value);
        });
    }

    public function scopeJob($query, $value)
    {
        return $query->whereHas('detail', function($query) use ($value) {
            $query->where('job', 'ILIKE', "%{$value}%");
        });
    }

    public function scopeMaritalStatus($query, $value)
    {
        return $query->whereHas('detail', function($query) use ($value) {
            $query->where('marital_status', $value);
        });
    }

    public function scopeHaveAChild($query, $value)
    {
        return $query->whereHas('detail', function($query) use ($value) {
            $query->where('have_a_child', 'ILIKE', "%{$value}%");
        });
    }

    public function scopeUseCigarette($query, $value)
    {
        return $query->whereHas('detail', function($query) use ($value) {
            $query->where('use_cigarette', 'ILIKE', "%{$value}%");
        });
    }

    public function scopeUseAlcohol($query, $value)
    {
        return $query->whereHas('detail', function($query) use ($value) {
            $query->where('use_alcohol', 'ILIKE', "%{$value}%");
        });
    }

    public function scopeEducation($query, $value)
    {
        return $query->whereHas('detail', function($query) use ($value) {
            $query->where('education_status', 'ILIKE', "%{$value}%");
        });
    }

    public function scopeSalary($query, $value)
    {
        return $query->whereHas('detail', function($query) use ($value) {
            $query->where('salary', 'ILIKE', "%{$value}%");
        });
    }

    public function scopePhysical($query, $value)
    {
        return $query->whereHas('detail', function($query) use ($value) {
            $query->where('physical_disability', 'ILIKE', "%{$value}%");
        });
    }

    public function scopeHeadCraft($query, $value)
    {
        return $query->whereHas('detail', function($query) use ($value) {
            $query->where('headscarf', 'ILIKE', "%{$value}%");
        });
    }

    public function scopeHasPhotos($query, $value)
    {
        if ((int) $value === 1) {
            return $query->whereHas('photos');
        }
    
        if ((int) $value === 0) {
            return $query->whereDoesntHave('photos');
        }
    
        return $query;
    }    
}
