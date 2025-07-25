<?php

namespace App\Models\Post;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'creator_user_id',
        'like_count',
        'favorite_count',
        'simile_count',
        'status'
    ];

    protected $casts = [
        'like_count'        => 'integer',
        'favorite_count'    => 'integer',
        'simile_count'      => 'integer',
        'status'            => 'integer'
    ];

    /**
     * @return HasMany
     */
    public function photos(): HasMany
    {
        return $this->hasMany(PostPhoto::class, 'post_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function creatorUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(PostActivityLog::class, 'post_id', 'id');
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
}
