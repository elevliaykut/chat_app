<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Post\Post;
use App\Models\User\UserActivityLog;
use App\Models\User\UserBlocked;
use App\Models\User\UserDetail;
use App\Models\User\UserPhoto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

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
        'like_count',
        'favorite_count',
        'smile_count',
        'gender',
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

    /**
     * @return HasOne
     */
    public function detail(): HasOne
    {
        return $this->hasOne(UserDetail::class, 'user_id', 'id');
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
        return $this->hasMany(UserActivityLog::class, 'activity_user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function photos(): HasMany
    {
        return $this->hasMany(UserPhoto::class, 'user_id', 'id');
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
        try {
            $parsedDate = Carbon::parse($date)->toDateString();
        } catch (\Exception $e) {
            return $query; // tarih bozuksa boş geç
        }

        return $query->whereDate('birth_date', $parsedDate);
    }

}
